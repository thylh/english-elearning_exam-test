@csrf

<div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $exam->title ?? '') }}" required>
    @error('title')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Type</label>
    <select name="type" class="form-control">
        <option value="practice" {{ old('type', $exam->type ?? '') == 'practice' ? 'selected' : '' }}>Practice</option>
        <option value="exam" {{ old('type', $exam->type ?? '') == 'exam' ? 'selected' : '' }}>Exam</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Band</label>
    <input type="text" name="band" class="form-control" value="{{ old('band', $exam->band ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Duration (minutes)</label>
    <input type="number" name="duration_minutes" class="form-control"
        value="{{ old('duration_minutes', $exam->duration_minutes ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control">{{ old('description', $exam->description ?? '') }}</textarea>
</div>

<div class="form-check mb-3">
    <input type="checkbox" name="published" value="1" class="form-check-input" id="published" {{ old('published', $exam->published ?? false) ? 'checked' : '' }}>
    <label for="published" class="form-check-label">Published</label>
</div>

<button class="btn btn-primary">Save</button>