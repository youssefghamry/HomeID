<?php
/**
 * @var bool $is_exists_section
 */
?>		<div class="gsf-theme-options-footer gsf-clearfix">
			<div class="gsf-theme-options-action">
				<button class="button gsf-theme-options-import" type="button"><?php esc_html_e('Import/Export', 'smart-framework'); ?></button>
				<?php if ($is_exists_section): ?>
					<button class="button gsf-theme-options-reset-section" type="button"><?php esc_html_e('Reset Section', 'smart-framework'); ?></button>
				<?php endif;?>
				<button class="button gsf-theme-options-reset-options" type="button"><?php esc_html_e('Reset Options', 'smart-framework'); ?></button>
			</div>
		</div>
	</form><!-- /.gsf-theme-options-form -->
</div><!-- /.gsf-theme-options-wrapper -->
