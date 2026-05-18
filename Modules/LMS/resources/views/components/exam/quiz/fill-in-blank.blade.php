{{-- @php
    $answers = [];
    $questionScore = $question['question_score'] ?? null;
    $isPassed = $isPassed ?? false;
@endphp
@foreach ($question['question_answers'] as $questionAnswer)
    @php
        $answerName = '';
        if (isset($questionAnswer['answer']['name'])) {
            $answerName = $questionAnswer['answer']['name'];
        } elseif (isset($questionAnswer['answer']->name)) {
            $answerName = $questionAnswer['answer']->name;
        }
        if ($answerName) {
            $answers[] = $answerName;
        }
        // Only pre-fill answer value when the quiz is locked (completed attempt)
        $prefillValue = $disabled == 'disabled' ? ($questionAnswer['take_answer']['value'] ?? '') : '';
    @endphp
    <label for="q-2-{{ $questionAnswer['id'] }}"
        class="flex items-start gap-3 dk-border-one rounded-lg p-3.5 cursor-pointer select-none">
        <input type="text" name="answers[{{ $question['id'] }}][{{ $questionAnswer['id'] }}][]"
            class="form-input focus-visible:outline-primary fill-in-blank"
            value="{{ $prefillValue }}" {{ $disabled }}>
    </label>
@endforeach

@if ($isPassed)
    <x-theme::exam.quiz.result-show :questionScore="$questionScore" :answers="$answers" />
    @php
        reset($answers);
    @endphp
@endif --}}
