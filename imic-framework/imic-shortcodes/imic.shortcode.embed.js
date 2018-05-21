function embedSelectedShortcode() {
        var shortcodeHTML;
	var shortcode_panel = document.getElementById('shortcode_panel');
	var current_shortcode = shortcode_panel.className.indexOf('current');
	if (current_shortcode != -1) {
		
		// SHORTCODE SELECT
		var shortcode_select = document.getElementById('shortcode-select').value;
		
		/////////////////////////////////////////
		////	SHORTCODE OPTION VARIABLES
		/////////////////////////////////////////
		
		// Button
		var button_type = document.getElementById('button-type').value;
		var button_colour = document.getElementById('button-colour').value;
		var button_text = document.getElementById('button-text').value;
		var button_url = document.getElementById('button-url').value;
		var button_extraclass = document.getElementById('button-extraclass').value;
		var button_size = document.getElementById('button-size').value;
		var button_target = "";
			
		if (document.getElementById('button-target').checked) {
			button_target = "_blank";
		} else {
			button_target = "_self";
		}
		
		// Icons
		var icon_image = document.getElementById('icon-image').value;
		
		// Staff
		var staff_number = document.getElementById('staff-number').value;
		var staff_order = document.getElementById('staff-order').value;	
		var staff_category = document.getElementById('staff-category').value;
		var staff_column = document.getElementById('staff-column').value;
		var staff_excerpt_length = document.getElementById('staff-excerpt-length').value;
		// Sermon
		var sermon_number = document.getElementById('sermon-number').value;
		var sermon_title = document.getElementById('sermon-title').value;
		var sermon_category = document.getElementById('sermon-category').value;
		var sermon_column = document.getElementById('sermon-column').value;				
		// Event
		var event_number = document.getElementById('event-number').value;
		var event_title = document.getElementById('event-title').value;
		var event_category = document.getElementById('event-category').value;	
		var event_style = document.getElementById('event-style').value;	
		var event_type = document.getElementById('event-type').value;	
               // Image Banner
		var imagebanner_image = document.getElementById('imagebanner-image').value;
		var imagebanner_width = document.getElementById('imagebanner-width').value;
		var imagebanner_height = document.getElementById('imagebanner-height').value;
		var imagebanner_extraclass = document.getElementById('imagebanner-extraclass').value;
		
		// Typography
		var typography_type = document.getElementById('typography-type').value;
		
		//Sidebar
		var sidebar_listing = document.getElementById('sidebar-listing').value;
		var sidebar_column = document.getElementById('sidebar-column').value;
		
		// Anchor Tags
		var anchor_href = document.getElementById('anchor-href').value;
		var anchor_xclass = document.getElementById('anchor-xclass').value;
		
		// Paragraph Tags
		var paragraph_xclass = document.getElementById('paragraph-xclass').value;
		
		// Span Tags
		var span_xclass = document.getElementById('span-xclass').value;		
		
		// Heading Tags
		var heading_size = document.getElementById('heading-size').value;
		var heading_extra = document.getElementById('heading-extra').value;
		
		// Container Tags
		var container_xclass = document.getElementById('container-xclass').value;
		
		// Section Tags
		var section_xclass = document.getElementById('section-xclass').value;
		
		// Divider Tags
		var divider_extra = document.getElementById('divider-extra').value;
		
		// Alert Box Tags
		var alert_type = document.getElementById('alert-type').value;
		var alert_close = "";
			
		if (document.getElementById('alert-type').checked) {
			alert_close = 'yes';
		} else {
			alert_close = 'no';
		}
		
		// Blockquote Box Tags
		var blockquote_name = document.getElementById('blockquote-name').value;	
		
		// Dropcap Box Tags
		var dropcap_type = document.getElementById('dropcap-type').value;
		
		// Code Box Tags
		var code_type = document.getElementById('code-type').value;				
		
		// Label Tags
		var label_type = document.getElementById('label-type').value;
				
		// Spacer Tags
		var spacer_size = document.getElementById('spacer-size').value;
		
		// Columns
		var column_options = document.getElementById('column-options').value;
		var column_xclass = document.getElementById('column-xclass').value;
		var column_animation = document.getElementById('column-animation').value;
			
		// Progress Bar
		var progressbar_percentage = document.getElementById('progressbar-percentage').value;
		var progressbar_text = document.getElementById('progressbar-text').value;
		var progressbar_value = document.getElementById('progressbar-value').value;
		var progressbar_type = document.getElementById('progressbar-type').value;
		var progressbar_colour = document.getElementById('progressbar-colour').value;
		
		// Counters
		var count_to = document.getElementById('count-to').value;
		var count_subject = document.getElementById('count-subject').value;
		var count_speed = document.getElementById('count-speed').value;
		var count_image = document.getElementById('count-image').value;
		var count_textstyle = document.getElementById('count-textstyle').value;
		
		// Tooltip
		var tooltip_text = document.getElementById('tooltip-text').value;
		var tooltip_link = document.getElementById('tooltip-link').value;
		var tooltip_direction = document.getElementById('tooltip-direction').value;
		
		// Tabs Tags
		var tabs_size = document.getElementById('tabs-size').value;
		var tabs_id = document.getElementById('tabs-id').value;
		
		// Accordion Tags
		var accordion_size = document.getElementById('accordion-size').value;
		var accordion_id = document.getElementById('accordion-id').value;	
		
		// Toggle Tags
		var toggle_size = document.getElementById('toggle-size').value;
		var toggle_id = document.getElementById('toggle-id').value;		
		
		// Table
		var table_type = document.getElementById('table-type').value;
		var table_head = document.getElementById('table-head').value;
		var table_columns = document.getElementById('table-columns').value;
		var table_rows = document.getElementById('table-rows').value;
		// Lists
		var list_type = document.getElementById('list-type').value;
		var list_icon = document.getElementById('list-icon').value;
		var list_items = document.getElementById('list-items').value;
		var list_extra = document.getElementById('list-extra').value;
				
		// Modal Box
		var modal_id = document.getElementById('modal-id').value;
		var modal_title = document.getElementById('modal-title').value;
		var modal_text = document.getElementById('modal-text').value;
		var modal_button = document.getElementById('modal-button').value;	
	   	// Fullscreen Video
		var fwvideo_videourl = document.getElementById('fwvideo-videourl').value;
		var fwvideo_autoplay = document.getElementById('fwvideo-autoplay').value;
		var full_width = "";
			
		if (document.getElementById('fw-video').checked) {
			full_width = 'yes';
		} else {
			full_width = 'no';
		}
	    // Calendar  Event Category
		var calendar_event_category = document.getElementById('calendar_event_category').value;	
	    // Calendar Google Calendar ID
		var calendar_googlecal_id = document.getElementById('calendar_googlecal_id').value;
	    // Calendar Google Calendar ID 1
		var calendar_googlecal_id1 = document.getElementById('calendar_googlecal_id1').value;
	    // Calendar Google Calendar ID 2
		var calendar_googlecal_id2 = document.getElementById('calendar_googlecal_id2').value;
		// calender filter
		var calendar_filter = document.getElementById('calender_filter').value;
		//Calendar Default View
		var calendar_view = document.getElementById('calendar-view').value;
	    // Form Email
		var form_email = document.getElementById('form_email').value;	
		/////////////////////////////////////////
		////	BUTTON SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-buttons') {
			shortcodeHTML = '<br/>[imic_button colour="'+button_colour+'" type="'+button_type+'" link="'+button_url+'" target="'+button_target+'" extraclass="'+button_extraclass+'" size="'+button_size+'"]'+button_text+'[/imic_button]<br/>';	
		}
		
		/////////////////////////////////////////
		////	FORM SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-form') {
			shortcodeHTML = '<br/>[imic_form form_email="'+form_email+'"]<br/>';	
		}
        /////////////////////////////////////////
		////	Calendar SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-calendar') {
			shortcodeHTML = '<br/>[event_calendar view="'+calendar_view+'" category_id="'+calendar_event_category+'" filter="'+calendar_filter+'" google_cal_id="'+calendar_googlecal_id+'" google_cal_id1="'+calendar_googlecal_id1+'" google_cal_id2="'+calendar_googlecal_id2+'"]<br/>';
                       
		}
		
		/////////////////////////////////////////
		////	ICON SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-icons') {
			shortcodeHTML = '[icon image="'+icon_image+'"]';	
		}
		
		/////////////////////////////////////////
		////	IMAGE BANNER SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-imagebanner') {
			shortcodeHTML = '<br/>[imic_image image="'+imagebanner_image+'" width="'+imagebanner_width+'" height="'+imagebanner_height+'" extraclass="'+imagebanner_extraclass+'"][/imic_image]<br/>';	
		}
		/////////////////////////////////////////
		////	TYPOGRAPHY SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-typography') {
			switch (typography_type){
				case 'typo-anchor':	shortcodeHTML = '<br/>[anchor href="'+anchor_href+'" extra="'+anchor_xclass+'"][/anchor]<br/>'; break;
				case 'typo-paragraph':	shortcodeHTML = '<br/>[paragraph extra="'+paragraph_xclass+'"][/paragraph]<br/>'; break;
				case 'typo-divider':	shortcodeHTML = '<br/>[divider extra="'+divider_extra+'"]<br/>'; break;
				case 'typo-heading':	shortcodeHTML = '<br/>[heading size="'+heading_size+'" extra="'+heading_extra+'"][/heading]<br/>'; break;
				case 'typo-alert':	shortcodeHTML = '<br/>[alert type="'+alert_type+'" close="'+alert_close+'"][/alert]<br/>'; break;
				case 'typo-blockquote':	shortcodeHTML = '<br/>[blockquote name="'+blockquote_name+'"][/blockquote]<br/>'; break;
				case 'typo-dropcap':	shortcodeHTML = '<br/>[dropcap type="'+dropcap_type+'"][/dropcap]<br/>'; break;
				case 'typo-code':	shortcodeHTML = '<br/>[code type="'+code_type+'"][/code]<br/>'; break;
				case 'typo-label':	shortcodeHTML = '<br/>[label type="'+label_type+'"][/label]<br/>'; break;
				case 'typo-container':	shortcodeHTML = '<br/>[container extra="'+container_xclass+'"][/container]<br/>'; break;
				case 'typo-spacer':	shortcodeHTML = '<br/>[spacer size="'+spacer_size+'"]<br/>'; break;
				case 'typo-span':	shortcodeHTML = '<br/>[span extra="'+span_xclass+'"][/span]<br/>'; break;
				case 'typo-section':	shortcodeHTML = '<br/>[section extra="'+section_xclass+'"][/section]<br/>'; break;
			}	
		}
		
		/////////////////////////////////////////
		////	COLUMNS SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-columns' && column_options == 'one_full') {
			shortcodeHTML = '<br/>[one_full extra="'+ column_xclass +'" anim="'+column_animation+'"]1 Text[/one_full]<br/>';	
		}
		if (shortcode_select == 'shortcode-columns' && column_options == 'two_halves') {
			shortcodeHTML = '<br/>[one_half extra="'+ column_xclass +'" anim="'+column_animation+'"]1/2 Text[/one_half]<br/>[one_half extra="'+ column_xclass +'" anim="'+column_animation+'"]1/2 Text[/one_half]<br/>';	
		}
		if (shortcode_select == 'shortcode-columns' && column_options == 'three_thirds') {
			shortcodeHTML = '<br/>[one_third extra="'+ column_xclass +'" anim="'+column_animation+'"]1/3 Text[/one_third]<br/>[one_third extra="'+ column_xclass +'" anim="'+column_animation+'"]1/3 Text[/one_third]<br/>[one_third extra="'+ column_xclass +'" anim="'+column_animation+'"]1/3 Text[/one_third]<br/>';	
		}
		if (shortcode_select == 'shortcode-columns' && column_options == 'four_quarters') {
			shortcodeHTML = '<br/>[one_fourth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/4 Text[/one_fourth]<br/>[one_fourth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/4 Text[/one_fourth]<br/>[one_fourth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/4 Text[/one_fourth]<br/>[one_fourth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/4 Text[/one_fourth]<br/>';	
		}
		if (shortcode_select == 'shortcode-columns' && column_options == 'six_one_sixths') {
			shortcodeHTML = '<br/>[one_sixth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/6 Text[/one_sixth]<br/>[one_sixth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/6 Text[/one_sixth]<br/>[one_sixth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/6 Text[/one_sixth]<br/>[one_sixth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/6 Text[/one_sixth]<br/>[one_sixth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/6 Text[/one_sixth]<br/>[one_sixth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/6 Text[/one_sixth]<br/>';	
		}
               
                if (shortcode_select == 'shortcode-columns' && column_options == 'one_halves_two_quarters') {
			shortcodeHTML = '<br/>[one_half extra="'+ column_xclass +'" anim="'+column_animation+'"]1/2 Text[/one_half]<br/>[one_fourth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/4 Text[/one_fourth]<br/>[one_fourth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/4 Text[/one_fourth]<br/>';	
		}
                if (shortcode_select == 'shortcode-columns' && column_options == 'three_two_thirds') {
			shortcodeHTML = '<br/>[one_third extra="'+ column_xclass +'" anim="'+column_animation+'"]1/3 Text[/one_third]<br/>[two_third extra="'+ column_xclass +'" anim="'+column_animation+'"]2/3 Text[/two_third]<br/>';	
		}
                if (shortcode_select == 'shortcode-columns' && column_options == 'two_thirds_one_thirds') {
			shortcodeHTML = '<br/>[two_third extra="'+ column_xclass +'" anim="'+column_animation+'"]2/3 Text[/two_third]<br/>[one_third extra="'+ column_xclass +'" anim="'+column_animation+'"]1/3 Text[/one_third]<br/>';	
		}
                if (shortcode_select == 'shortcode-columns' && column_options == 'two_quarters_one_halves') {
			shortcodeHTML = '<br/>[one_fourth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/4 Text[/one_fourth]<br/>[one_fourth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/4 Text[/one_fourth]<br/>[one_half extra="'+ column_xclass +'" anim="'+column_animation+'"]1/2 Text[/one_half]<br/>';	
		}
                if (shortcode_select == 'shortcode-columns' && column_options == 'one_quarters_one_halves_one_quarters') {
			shortcodeHTML = '<br/>[one_fourth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/4 Text[/one_fourth]<br/>[one_half extra="'+ column_xclass +'" anim="'+column_animation+'"]1/2 Text[/one_half]<br/>[one_fourth extra="'+ column_xclass +'" anim="'+column_animation+'"]1/4 Text[/one_fourth]<br/>';	
		}
				
		/////////////////////////////////////////
		////	MODAL BOX SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-modal') {
			shortcodeHTML = '<br/>[modal_box id="'+modal_id+'" title="'+modal_title+'" text="'+modal_text+'" button="'+modal_button+'"]<br/>';	
		}
		
		
		/////////////////////////////////////////
		////	PROGRESS BAR SHORTCODE OUTPUT
		/////////////////////////////////////////
				
		if (shortcode_select == 'shortcode-progressbar') {
		
			shortcodeHTML = '<br/>[progress_bar percentage="' + progressbar_percentage + '" name="' + progressbar_text + '" value="' + progressbar_value + '" type="' + progressbar_type + '" colour="' + progressbar_colour + '"]<br/>';
		
		}
		
		/////////////////////////////////////////
		////	COUNTERS SHORTCODE OUTPUT
		/////////////////////////////////////////
				
		if (shortcode_select == 'shortcode-counters') {
		
			shortcodeHTML = '<br/>[imic_count to="' + count_to + '" speed="' + count_speed + '" icon="' + count_image + '" textstyle="' + count_textstyle + '" subject="' + count_subject + '"]<br/>';
		
		}
		
		/////////////////////////////////////////
		////	SIDEBAR SHORTCODE OUTPUT
		/////////////////////////////////////////
				
		if (shortcode_select == 'shortcode-sidebar') {
		
			shortcodeHTML = '<br/>[sidebar id="' + sidebar_listing + '" column="'+sidebar_column+'"]<br/>';
		
		}
		
		/////////////////////////////////////////
		////	TOOLTIP SHORTCODE OUTPUT
		/////////////////////////////////////////
				
		if (shortcode_select == 'shortcode-tooltip') {
		
			shortcodeHTML = '<br/>[imic_tooltip link="' + tooltip_link + '" direction="' + tooltip_direction + '" title="'+ tooltip_text +'"]TEXT HERE[/imic_tooltip]<br/>';
		
		}
		
		/////////////////////////////////////////
		////	STAFF SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-staff') {
			shortcodeHTML = '<br/>[staff number="'+staff_number+'" category="'+staff_category+'" column="'+staff_column+'" order="'+staff_order+'" excerpt_length="'+staff_excerpt_length+'"]<br/>';	
		}
                /////////////////////////////////////////
		////	Sermon SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-sermon') {
			shortcodeHTML = '<br/>[sermon number="'+sermon_number+'" title="'+sermon_title+'" column="'+sermon_column+'" category="'+sermon_category+'"]<br/>';	
		}
                /////////////////////////////////////////
		////	Event SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-event') {
			shortcodeHTML = '<br/>[event number="'+event_number+'" title="'+event_title+'" category="'+event_category+'" style="'+event_style+'" type="'+event_type+'"]<br/>';	
		}
		
		/////////////////////////////////////////
		////	TABLE SHORTCODE OUTPUT
		/////////////////////////////////////////
	
		if (shortcode_select == 'shortcode-tables') {
			
			shortcodeHTML = '<br/>[htable type="' + table_type + '"]<br/>';
			
			if (table_head == "yes") {
				shortcodeHTML += '[thead]<br/>[trow]<br/>';
				for ( var hc = 0; hc < table_columns; hc++ ) {
					shortcodeHTML += '[thcol]HEAD COL ' + parseInt(hc + 1) + '[/thcol]<br/>';
				}
				shortcodeHTML += '[/trow]<br/>[/thead]<br/>';
			}
			shortcodeHTML += '[tbody]<br/>';
			
			for ( var r = 0; r < table_rows; r++ ) {
				shortcodeHTML += '[trow]<br/>';
				for ( var nc = 0; nc < table_columns; nc++ ) {
					shortcodeHTML += '[tcol]ROW ' + parseInt(r + 1) + ' COL ' + parseInt(nc + 1) + '[/tcol]<br/>';
				} 
				shortcodeHTML += '[/trow]<br/>';
			}
			
			shortcodeHTML += '[/tbody]<br/>';
			
			shortcodeHTML += '[/htable]<br/>';
		}
		
		/////////////////////////////////////////
		////	LIST SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-lists') {
			shortcodeHTML = '<br/>[list type='+ list_type +' extra='+ list_extra +']<br/>';
			
			for ( var li = 0; li < list_items; li++ ) {
				if((list_type == 'icon')||(list_type == 'inline')){
					shortcodeHTML += '[list_item icon="'+ list_icon +'" type="'+ list_type +'"]Item text '+ parseInt(li + 1) +'[/list_item]<br/>';
				}else if(list_type == 'desc'){
					shortcodeHTML += '[list_item_dt]Item text '+ parseInt(li + 1) +'[/list_item_dt][list_item_dd]Item text '+ parseInt(li + 1) +'[/list_item_dd]<br/>';
				}else{
					shortcodeHTML += '[list_item]Item text '+ parseInt(li + 1) +'[/list_item]<br/>';			
				}
			}
			
			shortcodeHTML += '[/list]<br/>';	
		}
		/////////////////////////////////////////
		////	ACCORDION SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-accordion') {
			
			shortcodeHTML = '<br/>[accordions id="'+ accordion_id +'"]<br/>';
			
			index = 0;
			for ( var hc = 0; hc < accordion_size; hc++ ) {
				if(index==0){ accordionClass='active'; accordionIn='in'; }else{ accordionClass=''; accordionIn='';}
				
				shortcodeHTML += '[accgroup]<br/>';
				shortcodeHTML += '[acchead id="'+ accordion_id + '" tab_id="'+ accordion_id + hc +'" class="'+ accordionClass +'"]Accordion Panel #' + parseInt(hc + 1) + '[/acchead]<br/>';
				shortcodeHTML += '[accbody tab_id="'+ accordion_id + hc +'" in="'+ accordionIn +'"]Accordion Body #' + parseInt(hc + 1) + '[/accbody]<br/>';
				shortcodeHTML += '[/accgroup]<br/>';
				index++;
			}
			
			shortcodeHTML += '[/accordions]<br/>';
		}
		
		/////////////////////////////////////////
		////	TOGGLE SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-toggle') {
			
			shortcodeHTML = '<br/>[toggles id="'+ toggle_id +'"]<br/>';
			
			for ( var hc = 0; hc < toggle_size; hc++ ) {
				shortcodeHTML += '[togglegroup]<br/>';
				shortcodeHTML += '[togglehead id="'+ toggle_id + '" tab_id="'+ toggle_id + hc +'"]Toggle Panel #' + parseInt(hc + 1) + '[/togglehead]<br/>';
				shortcodeHTML += '[togglebody tab_id="'+ toggle_id + hc +'"]Toggle Body #' + parseInt(hc + 1) + '[/togglebody]<br/>';
				shortcodeHTML += '[/togglegroup]<br/>';
			}
			
			shortcodeHTML += '[/toggles]<br/>';
		}
                /////////////////////////////////////////
		////	Full video SHORTCODE OUTPUT
		/////////////////////////////////////////
                 if (shortcode_select == 'shortcode-fwvideo') {
			
			shortcodeHTML = '[fullscreenvideo  videourl="'+ fwvideo_videourl +'" autoplay="'+fwvideo_autoplay+'" fullwidth="'+ full_width +'"]<br/>';
		
		}
		/////////////////////////////////////////
		////	TABS SHORTCODE OUTPUT
		/////////////////////////////////////////
		if (shortcode_select == 'shortcode-tabs') {
			shortcodeHTML = '<br/>[tabs]<br/>';
			
			shortcodeHTML += '[tabh]<br/>';
			index = 0;
			for ( var hc = 0; hc < tabs_size; hc++ ) {
				if(index==0){ tabClass='active'; }else{ tabClass=''; }
				shortcodeHTML += '[tab id="'+ tabs_id + hc +'" class="'+ tabClass +'"]TAB HEAD ' + parseInt(hc + 1) + '[/tab]<br/>';
				index++;
			}
			shortcodeHTML += '[/tabh]<br/>';
			
			shortcodeHTML += '[tabc]<br/>';
			flag = 0;
			for ( var r = 0; r < tabs_size; r++ ) {
				if(flag==0){ tabCClass='active'; }else{ tabCClass=''; }
				shortcodeHTML += '[tabrow id="'+ tabs_id + r +'" class="'+ tabCClass +'"]TAB CONTENT'+ parseInt(r + 1) +'[/tabrow]<br/>';
				flag++;
			}
			shortcodeHTML += '[/tabc]<br/>';
			
			shortcodeHTML += '[/tabs]<br/>';
		}
	}
	
		
	/////////////////////////////////////////
	////	TinyMCE Callback & Embed
	/////////////////////////////////////////
	
	if (current_shortcode != -1) {
		activeEditor = window.tinyMCE.activeEditor.id;
		parent.tinyMCE.execCommand('mceInsertContent', 
false,shortcodeHTML);
            parent.tinyMCE.activeEditor.windowManager.close();
	} else {
		tinyMCEPopup.close();		
	}
	return;
}
