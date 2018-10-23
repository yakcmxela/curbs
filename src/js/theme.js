(function($){

	var pageLocationTop;
	var pageLocationBottom;
	var windowHeight;
	var windowWidth;

	function defineViewport() {
		windowHeight = window.innerHeight;
		windowWidth = window.innerWidth;
		pageLocationTop = $(window).scrollTop();
		pageLocationBottom = pageLocationTop + windowHeight;
	}

	var scrollTriggers = function() {
		function animateElement(el, reset, trigger) {
			// el = element to add animated class
			// reset = reset when scrolled out of view (bool)
			// trigger = when to add class. percentage of element height (int) 
			//           for example: 50 would be 50% of the elements height.
			if(el.length) {
				defineViewport();
				var elTop = el.offset().top;
				var elHeight = el.innerHeight();
				var elBottom = elTop + elHeight;
				var elAnimated = (elHeight * (trigger / 100)) + elTop;
				var elReset = reset;

				if( pageLocationBottom >= elAnimated ) {
					el.addClass('animated');
				} else if( pageLocationBottom < elAnimated && elReset == true) {
					el.removeClass('animated');
				}
			}
		}
		// Els
		var featuredServices = $('#featured-services');
		// Calls
		animateElement(featuredServices, true, 0);
	};

	var getParameterByName = function(name, url) {
		if (!url) url = window.location.href;
		name = name.replace(/[\[\]]/g, "\\$&");
		var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
			results = regex.exec(url);
		if (!results) return null;
		if (!results[2]) return '';
		return decodeURIComponent(results[2].replace(/\+/g, " "));
	};
	
	var loginModal = function(){
		var loginModal = $('#account-login-modal');
		var bodyEl = $('body');
		
		var loginQueryString = getParameterByName('login');
		
		if(loginQueryString == 'failed'){
			loginModal.addClass('vis error');
			bodyEl.addClass('login-modal-open');
			bodyEl.removeClass('nav-open');
		}

		$('.open-login, .open-login > a').on('click', function(e){
			
			if( !MSWObject.logged_in ){
				e.preventDefault();
				loginModal.addClass('vis');
				bodyEl.addClass('login-modal-open');
				bodyEl.removeClass('nav-open');
			}
		});
		$('.close-login').on('click', function(e){
			loginModal.removeClass('vis');
			bodyEl.removeClass('login-modal-open');
		});
	};

	var curbfinder = function() {

		var oldObj = {
			man: $('#manufacturer-old'),
			model: $('#model-old'),
			notes: $('#notes-old'),
			part: $('#part-number-old'),
		};

		var newObj = {
			man: $('#manufacturer-new'),
			model: $('#model-new'),
			notes: $('#notes-new'),
			part: $('#part-number-new'),
		};

		var oldModelSelected = false;
		var newModelSelected = false;
		var oldManSelected = false;
		var newManSelected = false;
		var partNumbers = {
			oldTop: [],
			oldBottom: [],
			newTop: [],
			newBottom: []
		};

		var hvacQuery = function(type, oldNew) {
			return function(query, sync, async) {
				if(oldNew == 'old') {
					manufacturer = oldObj.man.val();
				} else if(oldNew == 'new') {
					manufacturer = newObj.man.val();
				} else {
					manufacturer = null;
				}
				return $.ajax({
					url: MSWObject.ajax_url,
					method: 'POST',
					data: {
						action: 'get_hvac_data',
						dataType: 'json',
						manufacturer: manufacturer,
						str: query,
						type: type,
					},
					success: function(data) {
						async(data);
					}
				});
			};
		};
		var hvacOldResults = null;
		var hvacNewResults = null;
		var hvacInfo = function(make, model, oldNew) {
			if(oldNew == 'old') {
				partNumbers.oldTop = null;
				partNumbers.oldBottom = null;
			} else {
				partNumbers.newTop = null;
				partNumbers.newBottom = null;
			}
			if(make == '' || model == '') {
				return;
			}
			$.ajax({
				url: MSWObject.ajax_url,
				method: 'POST',
				data: {
					action: 'get_hvac_product_info',
					dataType: 'json',
					make: make,
					model: model,
					oldNew: oldNew
				},
				async: true,
				success: function(data) {
					if(data == false) {
						if(oldNew == 'old') {
							oldObj.part.val('No results found, please try another Make/Model');
							oldObj.notes.html('');
						} else {
							newObj.part.val('No results found, please try another Make/Model');
							newObj.notes.html('');
						}
					} else {
						hvacInfoResults = $.map(data, function(el) { return el });
						if(oldNew == 'old') {
							hvacOldResults = hvacInfoResults;
							oldObj.part.val('');
							oldObj.notes.html('');
						} else {
							hvacNewResults = hvacInfoResults;
							newObj.part.val('');
							newObj.notes.html('');
						}
						
						if(hvacInfoResults.length == 1) {
							if(oldNew == 'old') {
								oldObj.part.val(hvacInfoResults[0].ccp_bottom);
							} else {
								newObj.part.val(hvacInfoResults[0].ccp_top);
							}	
						}

						var options;
						var allBottoms = '';
						if(hvacInfoResults.length > 1) {
							if(oldNew == 'old') {
								options += '<option>Select One (optional): </option>';
							} else {
								options += '<option>Select One (required): </option>';	
							}
						}
						var i = 1;
						$.each(hvacInfoResults, function() {
							if(i == hvacInfoResults.length) {
								allBottoms += this.ccp_bottom;
							} else {
								allBottoms += this.ccp_bottom + ', ';
							}
							i++;
							options += '<option>' + this.special_notes + '</option>';
						});

						if(oldNew == 'old') {
							oldObj.notes.html(options);
							oldObj.part.val(allBottoms);
						} else {
							newObj.notes.html(options);
						}
					}
				}
			});
		};

		var curbQuery = function(bottom, top) {
			$('.adapter-info').addClass('transition-out');
			$('#verify p').addClass('fade-out');
			$('.loading-icon').addClass('active');
			$('#verify').addClass('transition-out');
			var getData = $.ajax({
				url: MSWObject.ajax_url,
				method: 'POST',
				data: {
					action: 'get_curb',
					bottom: bottom,//TER 480B/600B
					top: top,//SAHC C40
				}
			});
			getData.fail(function(textStatus) {
				console.log(textStatus);
			});
			getData.success(function(data) {
				$('.adapter-info').html('');
				$('.adapter-info').removeClass('transition-out');
			});
			getData.done(function(data) {
				$('.adapter-info').addClass('transition-in');
				if(data == null) {
					$('.loading-icon').removeClass('active');
					$('.adapter-info').html('<p class="no-curbs">' + "We couldn't find a result for this make and model. Please try another combination." + '</p>');
					$('#verify').removeClass('transition-out');
					$('#verify').addClass('transition-in');
					$('.adapter-info').removeClass('transition-in');
					return;
				}
				var curbs = '';
				var i = 1;
				$.each(data, function(key, val) {
					if(val.type == 'curb') {
						var newCurb = '';
						newCurb += '<div class="curb">';
		 				newCurb += '<div class="info">';
		 				newCurb += '<h3>' + val.name + '</h3>';
		 				newCurb += '<h4>' + val.price + '</h4>';
		 				newCurb += '</div>';
		 				newCurb += '<div class="diagrams">';
		 				if(val.bottom.length) {
		 					newCurb += '<div class="diagram">';
		 					newCurb += '<a href="' + val.bottom + '" download>';
			 				newCurb += '<img src="' + val.bottom + '">';
			 				newCurb += '</a>';
			 				newCurb += '<a class="download" href="' + val.bottom + '" download>Download this diagram</a>';
			 				newCurb += '</div>';
		 				}
		 				newCurb += '<div class="diagram">';
		 				newCurb += '<a href="' + val.diagram + '" download>';
		 				newCurb += '<img src="' + val.diagram + '">';
		 				newCurb += '</a>';
		 				newCurb += '<a class="download" href="' + val.diagram + '" download>Download this diagram</a>';
		 				newCurb += '</div>';
		 				newCurb += '</div>';
		 				newCurb += '</div>';
		 				
		 				$('.adapter-info').append(newCurb);
						var stepHeight = $('#verify .step').innerHeight();
						var labelHeight = $('#verify .step-label').innerHeight();
						
						var adapterImage = $('#verify .diagram');
						if(adapterImage.length) {
							var adapterHeight = $('#verify .adapter-info').innerHeight();
						}

						if(i == data.length) {
							curbs += val.name;
						} else {
							curbs += val.name + ', ';
						}

						i++;
					} else if(val.type == 'diagram') {
						var noCurb = '';
						noCurb += '<div class="curb no-curb">';
						noCurb += '<div class="info">';
		 				noCurb += '<p>' + val.status + '</p>';
		 				noCurb += '<p>' + val.contact + '</p>';
		 				noCurb += '</div>';
		 				noCurb += '<div class="diagrams">';
		 				if(val.diagram) {
		 					noCurb += '<div class="diagram">';
			 				noCurb += '<a href="' + val.diagram + '" download>';
			 				noCurb += '<img src="' + val.diagram + '">';
			 				noCurb += '</a>';
			 				noCurb += '<a class="download" href="' + val.diagram + '" download>Download this diagram</a>';
			 				noCurb += '</div>';
		 				}
		 				noCurb += '</div>';
		 				noCurb += '</div>';
		 				$('.adapter-info').append(noCurb);
					}
			 		
				});
				$('html, body').animate({
					scrollTop: ($('#verify').offset().top)
				}, 1000);
				$('.adapters-verified .ginput_container .textarea').val(curbs);
				$('.loading-icon').removeClass('active');
				$('#verify').removeClass('transition-out');
				$('#verify').addClass('transition-in');
				$('.adapter-info').removeClass('transition-in');
			});
		};

		function searchForCurbs() {
			var bottom = oldObj.part.val();
			var top = newObj.part.val();
			if(bottom == '' || top == '') {
				return;
			} else {
				curbQuery(bottom, top);
				$('.submit').removeClass('nope');
			}
		}

		oldObj.man.typeahead({
			hint: true,
			highlight: true,
		},
		{
			limit: 12,
			source: hvacQuery('manufacturer', null),
			async: true,
		});

		newObj.man.typeahead({
			hint: true,
			highlight: true,
		},
		{
			limit: 12,
			source: hvacQuery('manufacturer', null),
			async: true,
		});

		oldObj.model.typeahead({
			hint: true,
			highlight: true,
		},
		{
			limit: 12,
			source: hvacQuery('unit_model_no', 'old'),
			async: true,
		});

		newObj.model.typeahead({
			hint: true,
			highlight: true,
		},
		{
			limit: 12,
			source: hvacQuery('unit_model_no', 'new'),
			async: true,
		});

		oldObj.man.bind('typeahead:select', 'typeahead:close', function(e, suggestion) {
			oldManSelected = true;
			if(oldManSelected == true && oldModelSelected == true) {
				var oldMan = oldObj.man.val();
				var oldModel = oldObj.model.val();
				hvacInfo(oldMan, oldModel, 'old');
			}
		});

		oldObj.model.bind('typeahead:select', 'typeahead:close', function(e, suggestion) {
			oldModelSelected = true;
			if(oldManSelected == true && oldModelSelected == true) {
				var oldMan = oldObj.man.val();
				var oldModel = oldObj.model.val();
				hvacInfo(oldMan, oldModel, 'old');
			}
		});

		newObj.man.bind('typeahead:select', 'typeahead:close', function(e, suggestion) {
			newManSelected = true;
			if(newManSelected == true && newModelSelected == true) {
				var newMan = newObj.man.val();
				var newModel = newObj.model.val();
				hvacInfo(newMan, newModel, 'new');
			}
		});

		newObj.model.bind('typeahead:select', 'typeahead:close', function(e, suggestion) {
			newModelSelected = true;
			if(newManSelected == true && newModelSelected == true) {
				var newMan = newObj.man.val();
				var newModel = newObj.model.val();
				hvacInfo(newMan, newModel, 'new');
			}
		});

		$('#search-curbs').on('click', function(e) {
			e.preventDefault();
			var bottom = oldObj.part.val();
			var top = newObj.part.val();
			if(bottom == '' || top == '') {
				$('.submit').addClass('nope');
			} else {
				curbQuery(bottom, top);
				$('.submit').removeClass('nope');
			}
		});

		oldObj.notes.on('change', function(e) {
			var oldNotes = oldObj.notes.val();
			var i = 1;
			var allBottoms = '';
			var matchFound = false;
			$.each(hvacOldResults, function() {
				if(i == hvacOldResults.length) {
					allBottoms += this.ccp_bottom;
				} else {
					allBottoms += this.ccp_bottom + ', ';
				}
				i++;
			});
			$.each(hvacOldResults, function() {
				if(oldNotes == this.special_notes) {
					oldObj.part.val(this.ccp_bottom);
					matchFound = true;
				}
			});
			if(matchFound == false) {
				oldObj.part.val(allBottoms);
			}
		});
		newObj.notes.on('change', function(e) {
			var newNotes = newObj.notes.val();
			$.each(hvacNewResults, function() {
				if(newNotes == this.special_notes) {
					newObj.part.val(this.ccp_top);
				}
			});
		});
	};
	
	var productGallery = function(){
		$('.product-gallery').magnificPopup({
			delegate: 'a',
			type: 'image',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1] // Will preload 0 - before current, and 1 after the current image
			}
		});
	};

	$(document).ready(function(){
		defineViewport();
		optimizedScroll.add(scrollTriggers);
		loginModal();
		productGallery();
		if($('body').hasClass('page-template-page_curbfinder')){
			curbfinder();
		}
	});

})(jQuery);