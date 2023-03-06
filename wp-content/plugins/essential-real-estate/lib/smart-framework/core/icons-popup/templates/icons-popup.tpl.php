<script type="text/html" id="tmpl-gsf-icons-popup">
	<div id="gsf-popup-icon-target" class="gsf-popup-icon-wrapper-hide">
		<div id="gsf-popup-icon-wrapper" class="gsf-popup-icon-wrapper g5u-popup-container">
			<div class="g5u-popup">
				<h4 class="g5u-popup-header">
					<strong><?php esc_html_e( 'Select An Icon', 'smart-framework' ); ?></strong>
				</h4>
				<div class="g5u-popup-body gsf-popup-icon-content">
					<div class="gsf-popup-icon-left">
						<div class="gsf-popup-icon-search">
							<input type="text" placeholder="<?php esc_attr_e( 'Search Icon...', 'smart-framework' ); ?>"/>
						</div>
						<div class="gsf-popup-icon-font">
							<select>
								<# _.each(data, function(item, index) { #>
								<option value="{{index}}">{{item.label}}</option>
								<# }); #>
							</select>
						</div>
						<div class="gsf-popup-icon-font-link">
							<div class="gsf-popup-icon-font-link-inner">
								<# _.each(data, function(item, fontId) { #>
								<div class="gsf-popup-icon-group-link" data-font-id="{{fontId}}">
									<ul>
										<# if (item.iconGroup.length > 1) { #>
										<li><a data-id="" href="#"><?php esc_html_e( 'All Icons', 'smart-framework' ); ?></a>
											<span>({{item.total}})</span>
										</li>
										<# } #>
										<# _.each(item.iconGroup, function(group) { #>
										<li><a data-id="{{group.id}}" href="#">{{group.title}}</a> <span>({{group.icons.length}})</span>
										</li>
										<# }); #>
									</ul>
								</div>
								<# }); #>
							</div>
						</div>
					</div>
					<div class="gsf-popup-icon-right">
						<div class="gsf-popup-icon-listing">
							<# _.each(data, function(item, fontId) { #>
							<div class="gsf-popup-icon-group-section" data-font-id="{{fontId}}">
								<h4 class="gsf-popup-icon-group-title"
								    data-msg-all="<?php esc_attr_e( 'All Icons', 'smart-framework' ); ?>"
								    data-msg-search="<?php esc_attr_e( 'Search result for "{0}"', 'smart-framework' ); ?>"><?php esc_html_e( 'All Icons', 'smart-framework' ); ?></h4>
								<ul></ul>
								<div class="gsf-popup-icon-group-load-more">
									<button type="button"
									        class="button button-primary"><?php esc_html_e( 'Load more...', 'smart-framework' ) ?></button>
								</div>
							</div>
							<# }); #>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>