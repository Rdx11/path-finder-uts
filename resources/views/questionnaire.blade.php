<form action="{{ route('questionnaire.submit') }}" method="POST">
    @csrf
    <label for="name">Name:</label>
    <input type="text" name="name" required>

    <label for="email">Email:</label>
    <input type="email" name="email" required>

    @foreach ($questions as $question)
        <div>
            <p>{{ $question->question }}</p>
            <input type="number" name="answers[{{ $question->id }}]" min="1" max="5" required>
        </div>
    @endforeach

    <button type="submit">Submit</button>
</form>
