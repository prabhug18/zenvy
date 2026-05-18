<?php

namespace Modules\LMS\Repositories\Courses\Topics\Quizzes;

use Illuminate\Http\Request;
use Modules\LMS\Enums\ExamType;
use Modules\LMS\Models\Auth\UserCourseExam;
use Modules\LMS\Models\Courses\Topics\Quiz;
use Modules\LMS\Models\Courses\Topics\Quizzes\QuestionAnswer;
use Modules\LMS\Models\Courses\Topics\Quizzes\QuizQuestion;
use Modules\LMS\Models\Courses\TopicType;
use Modules\LMS\Models\QuestionScore;
use Modules\LMS\Models\TakeAnswer;
use Modules\LMS\Repositories\BaseRepository;

class QuizRepository extends BaseRepository
{
    protected static $model = Quiz::class;

    protected static $modelOne = TopicType::class;

    protected static $exactSearchFields = [];

    protected static $rules = [
        'save' => [
            'chapter_id' => 'required',
            'title' => 'required|string',
            'duration' => 'required',
            'total_mark' => 'required|int',
            'pass_mark' => 'required|int',
            'quiz_type_id' => 'required',
        ],
        'update' => [],
    ];

    protected static $excludedFields = [
        'save' => ['chapter_id', 'topic_type', '_token', 'course_id'],
        'update' => ['chapter_id', 'topic_type', 'quiz_id', '_token', 'course_id'],
    ];

    public static function save($request): array
    {
        // Convert to collection if it's an array to use merge(), or handle as array
        $data = is_array($request) ? $request : $request->all();

        $topicType = static::topicType($data['topic_type'] ?? '');
        
        $data['topic_type_id'] = $topicType;
        $data['is_random_question'] = isset($data['is_random_question']) && $data['is_random_question'] === 'on' ? 1 : 0;
        $data['is_certificate'] = isset($data['is_certificate']) && $data['is_certificate'] === 'on' ? 1 : 0;

        // Define the common attributes for the topic
        $topicAttributes = [
            'chapter_id' => $data['chapter_id'] ?? null,
            'course_id' => $data['course_id'] ?? null,
        ];

        // Determine whether to update or save based on the presence of quiz_id
        if (isset($data['quiz_id']) && !empty($data['quiz_id'])) {
            $response = parent::update($data['quiz_id'], $data);

            // If successful, update the existing topic
            if ($response['status'] === 'success') {
                $response['data']->topic()->update($topicAttributes);
            }
        } else {
            $response = parent::save($data);

            // If successful, create a new topic
            if ($response['status'] === 'success') {
                $response['data']->topic()->create($topicAttributes);
            }
        }

        return $response;
    }

    /**
     * @param  int  $id
     * @param  array  $data
     * @return {array}
     */
    public static function update($id, $data): array
    {
        static::$rules['update'] = [
            'title' => 'required|unique:quizzes,title,' . $id,
        ];

        return parent::update($id, $data);
    }

    /**
     *  topicType
     *
     * @param  string  $typeName
     * @return int
     */
    public static function topicType($typeName)
    {
        $topicType = static::$modelOne::where('slug', $typeName)->select('id')->first();

        return $topicType->id;
    }

    /**
     * Method submitQuizAnswer
     *
     * @param  int  $quizId
     * @param  string  $type
     * @param  Request  $request
     */
    public function submitQuizAnswer($quizId, $type, $request)
    {

        $checkUserQuiz = UserCourseExam::with('quiz')->where(['user_id' => authCheck()->id, 'quiz_id' => $quizId])->first();
        $total_retake = $checkUserQuiz?->quiz?->total_retake ?? 1;
        $attempt_number = $checkUserQuiz->attempt_number ?? 0;

        if ($total_retake > $attempt_number) {
            $userQuiz = $this->examStore($quizId, $request);
            $questionId = $request->question_id;

            // Initialize the score
            $score = 0;

            // Process the quiz based on type using match
            $score = match ($type) {
                'multiple-choice' => $this->handleMultipleChoice($request, $questionId, $quizId, $userQuiz),
                'single-choice' => $this->handleSingleChoice($request, $questionId, $quizId, $userQuiz),
                'fill-in-blank' => $this->handleFillInBlank($request, $questionId, $quizId, $userQuiz),
                default => ['status' => 'error',  'message' => translate('Invalid question type.')],
            };

            return ['status' => true, 'score' => $score];
        }

        return ['status' => false];
    }

    /**
     * Method takeAnswer
     *
     * @param  array  $data
     */
    public function takeAnswer($data)
    {
        // Unique key scoped to exam + question + answer so different users' records are isolated
        TakeAnswer::updateOrCreate([
            'user_course_exam_id' => $data['user_quiz_id'],
            'quiz_question_id'   => $data['quiz_question_id'] ?? null,
            'question_answer'    => $data['question_answer'] ?? null,
        ], [
            'user_course_exam_id' => $data['user_quiz_id'],
            'quiz_question_id'   => $data['quiz_question_id'],
            'question_answer'    => $data['question_answer'],
            'value'              => $data['value'] ?? null,
        ]);
    }

    public function finalSubmit($id, $request)
    {
        $userQuiz = $this->examStore($id, $request);
        $userId = authCheck()->id;

        $totalScore = QuestionScore::where(['quiz_id' => $userQuiz->quiz_id, 'user_id' => $userId])->sum('score');
        
        if ($userQuiz->update([
            'attempt_number' => $userQuiz->attempt_number += 1,
            'score' => $totalScore,
        ])) {
            // Check for Certificate Eligibility
            $userQuiz->load('course.courseSetting');
            $course = $userQuiz->course;
            $hasCertificate = $course?->courseSetting?->is_certificate ?? 0;

            $quiz = $userQuiz->quiz;
            if ($quiz && $hasCertificate && $totalScore >= $quiz->pass_mark) {
                // Auto-generate certificate via CertificateRepository
                app(\Modules\LMS\Repositories\Certificate\CertificateRepository::class)->requestCertificate($userQuiz->id);
            }

            return ['status' => 'success'];
        }
    }

    /**
     * takeAnswerDelete
     *
     * Scoped to the current user's exam so other students' answers are never affected.
     *
     * @param  int  $id          quiz_question_id
     * @param  int  $userQuizId  user_course_exam_id
     * @return void
     */
    public function takeAnswerDelete($id, $userQuizId = null)
    {
        $query = TakeAnswer::where('quiz_question_id', $id);
        if ($userQuizId) {
            $query->where('user_course_exam_id', $userQuizId);
        }
        $query->delete();
    }

    /**
     * questionScore
     *
     * @param  int  $id
     * @return void
     */
    public function questionScoreUpdate($id, $userId = null)
    {
        $userId = $userId ?? authCheck()->id;
        $questionScore = QuestionScore::where(['question_id' => $id, 'user_id' => $userId]);
        $questionScore->delete();
    }

    /**
     * examStore
     *
     * @param  int  $id
     * @param  Request  $request
     * @return object
     */
    public function examStore($id, $request)
    {
        return UserCourseExam::updateOrCreate(['user_id' => authCheck()->id ?? null, 'quiz_id' => $id ?? null], [
            'user_id' => authCheck()->id,
            'quiz_id' => $id,
            'course_id' => $request->course_id,
            'chapter_id' => $request->chapter_id,
            'topic_id' => $request->topic_id,
            'exam_type' => ExamType::QUIZ,
        ]);
    }

    public function quizById($id, $coursesId = null)
    {
        $quiz = static::$model::query();
        if ($coursesId) {
            $quiz->withWhereHas('topic', function ($query) use ($coursesId) {
                $query->whereIn('course_id', $coursesId);
                $query->with('course');
            });
        }

        return $quiz->with('studentQuizzes')->where('id', $id)->first();
    }

    // Define the handling methods

    private function handleMultipleChoice($request, $questionId, $quizId, $userQuiz)
    {
        // Fetch the correct answers
        $correctAnswers = QuestionAnswer::where(['quiz_question_id' => $questionId, 'correct' => true])->get()->pluck('id')->toArray();
        $rightCount = 0;
        $wrongCount = 0;

        // Clear previous answers for this question scoped to the current user's exam
        $this->takeAnswerDelete($questionId, $userQuiz->id);

        if (!empty($request->answers)) {
            foreach ($request->answers as $answer) {
                $checkAnswer = QuestionAnswer::with('quizQuestion')->find($answer);
                if ($checkAnswer) {
                    // Increment right or wrong counters
                    $checkAnswer->correct ? $rightCount++ : $wrongCount++;

                    // Save the user's answer
                    $this->takeAnswer([
                        'user_quiz_id'    => $userQuiz->id,
                        'quiz_question_id' => $questionId,
                        'question_answer'  => $answer,
                    ]);
                }
            }

            // Calculate score based on correctness
            return $this->calculateScore($rightCount, $wrongCount, $correctAnswers, $questionId, $quizId);
        }

        return 0; // No score
    }

    private function handleSingleChoice($request, $questionId, $quizId, $userQuiz)
    {
        $answerId = $request->answers[0] ?? null;
        $score = 0;

        // Clear previous answers scoped to the current user's exam
        $this->takeAnswerDelete($questionId, $userQuiz->id);

        if ($answerId) {
            $answer = QuestionAnswer::with('quizQuestion')->find($answerId);
            if ($answer) {
                // Save the user's answer
                $this->takeAnswer([
                    'user_quiz_id'    => $userQuiz->id,
                    'quiz_question_id' => $questionId,
                    'question_answer'  => $answerId,
                ]);

                // Calculate score if the answer is correct
                $realQuestionId = QuizQuestion::find($questionId)?->question_id ?? $questionId;

                if ($answer->correct) {
                    $score = $answer->quizQuestion->mark;
                    QuestionScore::updateOrCreate(
                        ['quiz_id' => $quizId, 'question_id' => $realQuestionId, 'user_id' => authCheck()->id],
                        [
                            'score'  => $score,
                            'status' => true,
                        ]
                    );
                } else {
                    $this->questionScoreUpdate($realQuestionId, authCheck()->id);
                }
            }
        }

        return $score;
    }

    private function handleFillInBlank($request, $questionId, $quizId, $userQuiz)
    {
        $rightCount = 0;
        $wrongCount = 0;

        // Clear previous answers scoped to the current user's exam
        $this->takeAnswerDelete($questionId, $userQuiz->id);

        // Only process answers belonging to the CURRENT question being submitted.
        // The request payload keys are quiz_question IDs. We must only evaluate
        // the entry matching $questionId to avoid cross-contaminating other questions.
        $answersForThisQuestion = $request->answers[$questionId] ?? [];

        if (empty($answersForThisQuestion)) {
            // Nothing submitted for this question — mark as wrong and return 0
            $this->questionScoreUpdate(
                QuizQuestion::find($questionId)?->question_id ?? $questionId,
                authCheck()->id
            );
            return 0;
        }

        foreach ($answersForThisQuestion as $index => $value) {
            $value = is_array($value) ? implode(',', $value) : $value;
            $questionAnswer = QuestionAnswer::with('answer')
                ->where(['quiz_question_id' => $questionId, 'id' => $index])
                ->first();

            if ($questionAnswer) {
                // Case-insensitive, trimmed comparison for robustness
                $correct = strtolower(trim($questionAnswer->answer->name)) === strtolower(trim($value));
                $correct ? $rightCount++ : $wrongCount++;

                // Save the user's answer
                $this->takeAnswer([
                    'user_quiz_id'     => $userQuiz->id,
                    'quiz_question_id' => $questionId,
                    'question_answer'  => $index,
                    'value'            => $value,
                ]);
            } else {
                $wrongCount++;
            }
        }

        // Calculate score based on correctness
        return $this->calculateScore($rightCount, $wrongCount, [], $questionId, $quizId);
    }

    private function calculateScore($rightCount, $wrongCount, $correctAnswers, $questionId, $quizId)
    {
        // Assuming you have a way to get the mark for the question
        $mark = 0;
        $userId = authCheck()->id;

        $realQuestionId = QuizQuestion::find($questionId)?->question_id ?? $questionId;

        if ($rightCount > 0 && $wrongCount === 0) {
            // All answers are correct
            $mark = $this->getMarkForQuestion($questionId);
            QuestionScore::updateOrCreate(
                ['quiz_id' => $quizId, 'question_id' => $realQuestionId, 'user_id' => $userId],
                [
                    'score' => $mark,
                    'status' => true,
                ]
            );
        } else {
            // Handle case where answers are incorrect
            $this->questionScoreUpdate($realQuestionId, $userId);
        }

        return $mark; // Return calculated score
    }

    private function getMarkForQuestion($questionId)
    {
        // Bug fix: $questionId is a quiz_questions.id — query QuizQuestion directly
        // The old code queried question_answers by id, which returned null for non-matching IDs
        $question = QuizQuestion::find($questionId);
        return $question ? $question->mark : 0;
    }
}
