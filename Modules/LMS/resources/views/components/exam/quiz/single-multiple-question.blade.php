@php
    $inputType = $question['question_type'] == 'multiple-choice' ? 'checkbox' : 'radio';
    $answers = [];
    $questionScore = $question['question_score'] ?? null;
    $isPassed = $isPassed ?? false;
@endphp

@foreach ($question['question_answers'] as $questionAnswer)
    @php
        $answers[] = $questionAnswer['correct'] == 1 ? $questionAnswer['answer']['name'] : '';
        // Only pre-check answers when the quiz is locked (completed attempt), not on active attempts
        $isChecked = ($disabled == 'disabled') && $questionAnswer['take_answer'];
    @endphp
    <li class="option">
        <label for="q-1-{{ $questionAnswer['id'] }}"
            class="flex items-start gap-3 dk-border-one rounded-lg p-3.5 cursor-pointer select-none">
            <input type="{{ $inputType }}" name="answers[]" {{ $isChecked ? 'checked' : '' }}
                value="{{ $questionAnswer['id'] }}" id="q-1-{{ $questionAnswer['id'] }}" {{ $disabled }}
                class=" {{ $inputType == 'checkbox' ? 'lms-checkbox-check checkbox checkbox-primary' : 'lms-radio-check radio radio-primary' }} quizSelectAnswer  ">
            <span
                class="text-heading dark:text-white font-medium leading-none">{{ $questionAnswer['answer']['name'] }}</span>
        </label>
    </li>
@endforeach
@if ($isPassed)
    <x-theme::exam.quiz.result-show :questionScore="$questionScore" :answers="$answers" />
    @php
        reset($answers);
    @endphp
@endif
