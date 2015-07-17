( function( $ ) {
	$(window).on('load', function(){
		wp.pixSortableBuilder = {

			init: function() {

				$('.pix-customize-builder').each(function(){

					var $builder = $(this),
						$area = $('.pix-builder-area', $builder),
						$panel = $('.pix-panel-builder.to-clone', $builder),
						$hidden = $('input[type="hidden"]', $builder),
						triggerChange, val;

					var updateFields = function(){
						$area.find('[name]').each(function(){
							var name = $(this).attr('name'),
								ind = $(this).parents('.pix-panel-builder').eq(0).index();
							name = name.replace(/(.*?)\[(.*?)\]\[/g, '$1['+ ( ind ) +'][');

							$(this).attr('name', name);

							clearTimeout(triggerChange);
							triggerChange = setTimeout(function(){
								val = $('input, select', $area).serialize();
								$hidden.val(val).trigger('change');
							},50);
						});
					};

					$area.accordion({
						header: "> div.pix-panel-builder > h3",
						collapsible: true,
						active: false,
						heightStyle: "content"
					}).sortable({
						axis: "y",
						handle: "h3",
						stop: function( event, ui ) {
							$( this ).accordion( "refresh" );
							updateFields();
						}
					});

					var areaFields = function(){

						$area.find('input').on('keypress', function(){
							clearTimeout(triggerChange);
							triggerChange = setTimeout(function(){
								val = $('input, select', $area).serialize();
								$hidden.val(val).trigger('change');
							},50);
						});
						$area.find('select').on('change', function(){
							clearTimeout(triggerChange);
							triggerChange = setTimeout(function(){
								val = $('input, select', $area).serialize();
								$hidden.val(val).trigger('change');
							},50);
						});
						$area.find('input').on('click keypress keyup', function(e){
							e.stopPropagation();
						});
						$area.find('.pix-panel-builder').each(function(){
							var $panel_this = $(this);
							$('.delete-panel', $panel_this).off('click');
							$('.delete-panel', $panel_this).on('click', function(){
								$panel_this.animate({
									height: 0,
									opacity: 0
								},250, function(){
									$panel_this.remove();
									$area.accordion("refresh");
									clearTimeout(triggerChange);
									triggerChange = setTimeout(function(){
										val = $('input, select', $area).serialize();
										$hidden.val(val).trigger('change');
									},50);
									updateFields();
								});
							});
						});

						$area.find('.add-icon-button').on('click', function(e){
							e.preventDefault();
							var $panel_this = $(this).parents('.pix-panel-builder').eq(0);
							addIcon($panel_this);
						});

						$area.find('.remove-icon-button').on('click', function(){
							var $panel_this = $(this).parents('.pix-panel-builder').eq(0);
							$('.icon-placeholder', $panel_this).val('');
							$('.div-icon-placeholder', $panel_this).attr('class', 'div-icon-placeholder');
							clearTimeout(triggerChange);
							triggerChange = setTimeout(function(){
								val = $('input, select', $area).serialize();
								$hidden.val(val).trigger('change');
							},50);
						});

					};

					var filterIcon = function(){
						$(document).off('keyup', '#pix-icon-finder input[type="text"]');
						$(document).on('keyup', '#pix-icon-finder input[type="text"]', function(){
							var $input = $(this),
								$iconList = $('#pix-icon-render'),
								val,
								setInput;

							var showFilteredIcons = function(){
								val = $input.val();

								if ( val == '' ) {
									$('> div', $iconList).show();
								} else {
									$('> div', $iconList).not('[data-keys*="'+val+'"]').hide();
									$('> div[data-keys*="'+val+'"]', $iconList).show();
								}
							};
							clearTimeout(setInput);
							setInput = setTimeout(showFilteredIcons, 50);
						});
					};

					var selectIcon = function(){
						$('#list-icon .the-list > div').off('click')
							.on('click', function(){
							var icon;
							if ( $(this).hasClass('selected') ) {
								$(this).removeClass('selected');
							} else {
								icon = $(this).attr('class');
								$('#list-icon .the-list > div.selected').removeClass('selected');
								$(this).addClass('selected');
							}
						});
					};

					var loadIconList = function(){
						$(document).off('change', '#pix-icon-finder select');
						$(document).on('change', '#pix-icon-finder select', function(){
							//ajaxLoaders(true);
							var t = $(this),
								url = $('option:selected', t).data('url');

							var ajaxReqIcon = function(){
								$.ajax({
						            url: url,
						            type: "POST",
						            cache: false,
						            success: function(loadeddata) {
										var html = $("<div/>").append(loadeddata.replace(/<script(.|\s)*?\/script>/g, "")).html();
										$('#pix-icon-render')
											.html(html)
											.animate({opacity:1},250);
										selectIcon();
										filterIcon();
						            }
								});
							};

							$('#pix-icon-render').animate({opacity:0},250, function(){
								ajaxReqIcon();
							});
						});
					}

					var addIcon = function($panel_this){
						var div = $('#list-icon');

						div.dialog({
							resizable: false,
							draggable: false,
							modal: true,
							dialogClass: 'pix-modal',
							width: 'auto',
							position: { my: "center", at: "center", of: window },
							title: "Select an icon",
							buttons: {
								"Select": function() {
									var icon = $('#list-icon .the-list > div.selected').data('iconname');
									if ( typeof icon != 'undefined' && icon !== '' ) {
										$('.icon-placeholder', $panel_this).val(icon);
										$('.div-icon-placeholder', $panel_this).attr('class', 'div-icon-placeholder ' + icon);
									}
									$('#list-icon .the-list > div.selected').removeClass('selected');
									$( this ).dialog( "close" );
									clearTimeout(triggerChange);
									triggerChange = setTimeout(function(){
										val = $('input, select', $area).serialize();
										$hidden.val(val).trigger('change');
									},50);
								}
							},
							open: function( event, ui ) {
								loadIconList();
								if ( $('#pix-icon-render').html() == '' )
									$('#pix-icon-finder select').trigger('change');
							}
						});
					};

					areaFields();

					$('.builder-add-element', $builder).on('click', function(){

						var $clone = $panel.clone();

						$clone.find('[name*="[clone]"]').each(function(){
							var name = $(this).attr('name'),
								len = $('.pix-panel-builder', $area).length;
							name = name.replace(/\[clone\]/g, '['+len+']');

							$(this).attr('name', name);
						});

						$area.append($clone.removeClass('to-clone'));

						$area.accordion("refresh");

						areaFields();

						clearTimeout(triggerChange);
						triggerChange = setTimeout(function(){
							val = $('input, select', $area).serialize();
							$hidden.val(val).trigger('change');
						},50);

					});

				});

			}

		};

		wp.pixSortableBuilder.init();

	});

} )( jQuery );
