<div class="form-group">
	<label>Title</label>
	<input type="text" class="form-control" maxlength="255" name="title" ng-model="data.title" value="<?= $post -> title; ?>" />
</div>

<div class="form-group">
	<label>Content</label>
	<wysiwyg textarea-id="content_" ng-model="data.content" textarea-class="form-control"  textarea-height="150px" action-tracker="form_media_description" textarea-name="textareaQuestion" textarea-required enable-bootstrap-title="true" textarea-menu="wysiwig_options"  ></wysiwyg>

	<textarea type="text" class="form-control" name="content" ng-show="false"  ><?= $post -> content; ?></textarea>
</div>

<div class="form-group">
	<label>Header Image</label>
	<input type="file" name="header_image" accept="image/*" />
</div>

<div class="form-group">
	<input type="checkbox" name="is_published" ng-model="data.is_published" value="1" <?= ($post -> is_published || !$post -> post_id) ? 'checked' : ''; ?> cb-true-value="1" cb-false-value="0" ng-true-value="1" ng-false-value="0"  ng-checked="<?= ($post -> is_published || !$post -> post_id) ? 1 : 0; ?>" /> Is Published
</div>

<!--Create CSRF Token For Security -->
<?= $this->CSRF->getCSRFTokenInput(); ?>