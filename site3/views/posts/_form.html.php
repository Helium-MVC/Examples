<div class="form-group">
	<label>Title</label>
	<input type="text" class="form-control" maxlength="255" name="title" value="<?= $post -> title; ?>" />
</div>

<div class="form-group">
	<label>Content</label>
	<textarea type="text" class="form-control" name="content"  ><?= $post -> content; ?></textarea>
</div>

<div class="form-group">
	<label>Header Image</label>
	<input type="file" name="header_image" accept="image/*" />
</div>

<div class="form-group">
	<input type="checkbox" name="is_published" value="1" <?= ($post -> is_published || !$post -> post_id) ? 'checked' : ''; ?> /> Is Published
</div>