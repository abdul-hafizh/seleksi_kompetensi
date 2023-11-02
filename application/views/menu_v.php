<?php
	$uri = $this->uri->segment(1);
	$uri2 = $this->uri->segment(2);
	$uri3 = $this->uri->segment(3);

	foreach ($main_menu as $key => $value) {
		$parent = $value['child'];
		$class = "";
		$show = "";

		if (isset($uri)) {
			$class = ($uri == $key) ? "collapsed active" : "";
			$show = ($uri == $key) ? "collapsed show" : "";
		}

		if (empty($parent)) { ?>

			<li class="nav-item">
				<a class="nav-link menu-link <?php echo $class ?>" href="<?php echo site_url("/") . $key; ?>">
					<i class="<?php echo $value['icon'] ?>"></i> <span data-key="<?php echo $value['data_key'] ?>"><?php echo $value['label'] ?></span>
				</a>
			</li>

		<?php } else {
			$buka = false;
			if (isset($uri) && !$buka) {
				$buka = ($uri == $key) ? true : false;
			}
		?>

			<li class="nav-item">
				<a class="nav-link menu-link <?php echo $class; ?>" href="#<?php echo $value['url_path'] ?>" data-bs-toggle="collapse" role="button" aria-expanded="<?php echo ($buka) ? '' : '' ?>" aria-controls="sidebarPages">
					<i class="<?php echo $value['icon'] ?>"></i> <span data-key="<?php echo $value['data_key'] ?>"><?php echo $value['label'] ?> </span>
				</a>
				<div class="collapse menu-dropdown <?php echo $show; ?>" id="<?php echo $value['url_path'] ?>">
					<ul class="nav nav-sm flex-column">
					<?php
					foreach ($parent as $key2 => $value2) {
						$parent2 = $value2['child'];
						$class2 = "";
						
						if (isset($uri2)) {
							$class2 = ($uri . "/" . $uri2 == $key2) ? "active" : "";
						}

						if (empty($parent2)) { ?>

							<li class="nav-item">
								<a href="<?php echo site_url("/") . $key2; ?>" class="nav-link <?php echo $class2; ?>" aria-expanded="<?php echo ($buka) ? '' : '' ?>" aria-controls="<?php echo $value2['url_path'] ?>" data-key="<?php echo $value2['data_key'] ?>"> <?php echo $value2['label'] ?></a>
							</li>

						<?php } else {

							$buka2 = false;
							if (isset($uri2) && !$buka2) {
								$buka2 = ($uri . "/" . $uri2 == $key2) ? true : false;
							}
						?>

							<li class="nav-item">
								<a href="#<?php echo $value2['url_path'] ?>" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="<?php echo ($buka2) ? '' : '' ?>" aria-controls="<?php echo $value2['url_path'] ?>" data-key="<?php echo $value2['data_key'] ?>"> <?php echo $value2['label'] ?></a>
								<div class="collapse menu-dropdown" id="<?php echo $value2['url_path'] ?>">
									<ul class="nav nav-sm flex-column">
										<?php foreach ($parent2 as $key3 => $value3) { ?>
											<li class="nav-item">
												<a href="<?php echo site_url() ?>/<?php echo $key3 ?>" class="nav-link" data-key="<?php echo $value3['data_key'] ?>"> <?php echo $value3['label'] ?> </a>
											</li>								
										<?php } ?>
									</ul>
								</div>
							</li>
						<?php } 
					} ?>
					</ul>
				</div>
			</li>

		<?php } 
	} ?>

	<script type="text/javascript">
		$('a').click(function(e){
			var url = $(this).attr('href');
			var url_parts = url.replace(/\/\s*$/,'').split('/');
			if ( $.inArray('panduan', url_parts) > -1 ) {
				e.preventDefault();
				window.open($(this).attr('href'),'_blank')
			}

		})
	</script>
