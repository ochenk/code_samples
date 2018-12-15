var dpColWidth=93;

jQuery(document).ready(function($){

  var str = $('.breadcrumb').html();
  if (str.indexOf("midcourse-review") < 0){
    $('.breadcrumb').empty().html("<a href='/'>Home</a> » <a href='/2020/data-search/'>Data Search</a> » Search the Data");
  }
  var hash = location.hash.replace('#', '');
  var hashArray = hash.split(';');
  $.each(hashArray, function(i,v){
    var hashItem = v.split('=');
    if(hashItem[0]=='srch'){
      $('#ds-search-query-pen').attr('value',hashItem[1]);
    }else if(hashItem[0]=='objid'){
      $('#ds-search-objid-pen').attr('value',hashItem[1]);
      $.get("/2020/data-search/Search-the-Data/getobjtitle/"+hashItem[1], function(returnVal) {
        $('#ds-search-objid-pen').empty().html(returnVal);
        $('.ds-search-tag-name-obj').empty().html(returnVal);
      });
    }else{
      $('input#'+hashItem[0]+'-'+hashItem[1]).prop('checked', true);
    }
  });

  
  $('.ds-results-panel .ds-result').empty().html("<div style='width=100%;text-align:center;margin:10px 0 20px 0;'><img src='/sites/all/modules/custom/ch_datasearch/loader.gif'/></div>");
  $('.ds-view-header').empty();
  $('.ds-pager-desc').empty();

  $('.ds-search-button').click(function(){
    var searchq = $('.ds-input-text').val();
    if(searchq.length > 0){
      $('#ds-search-query-pen').attr('value', searchq);
      $('.ds-filter-panel input:checkbox').first().change();
      AnalyticsLogicLayer.push({event:"ds-keyword-search",eventCategory:"AJAX",keyword:searchq});
    }
  });
  $(document).on("keypress", "#ds-keyword-search", function(e) {
     if (e.which == 13) {
         $('.ds-search-button').click();
     }
  });

  $('button.ds-display-prefs-drawer').click(function(e){
    $('.customize-display-dropdown').slideToggle();
  })

  var xhr;
  var xhrTrip=0;

  // any checkbox change in left filter
  $('.ds-filter-panel input:checkbox').change(function(e, data){
    var tagang='';
    var srcgang='';
    var sldgang='';
    var sexgang='';
    var agegang='';
    var objid='';
    var lhi=0;
    var mes=0;
    var statemap=0;
    var hdisp=0;
    var tachecks=0;
    var srcchecks=0;
    var sldchecks=0;
    var sexchecks=0;
    var agechecks=0;
    var summaryItems = [];
    var searchq='';
    var pageNum=1
    $('.ds-no-results-content').empty();

    if(typeof(data) != "undefined" && data !== null){
      if(typeof(data.pageNum) != "undefined" && data.pageNum !== null){pageNum=data.pageNum;}
    }
    
    $('.ds-filter-panel input.topic-area').each(function(i,v){
      if($(this).is(':checked')){
        tagang += ($(this).val()) + ',';
        summaryItems.push(new Array($(this).attr("alt"),"topic-area",$(this).val()));
        tachecks++;
      }
      $('#field_topic_area .check_count').empty().html(tachecks);
    });
    $('.ds-filter-panel input.source').each(function(i,v){
      if($(this).is(':checked')){
        srcgang += ($(this).val()) + ',';
        summaryItems.push(new Array($(this).attr("alt"),"source",$(this).val()));
        srcchecks++;
      }
      $('#field_data_sources .check_count').empty().html(srcchecks);
    });
    $('.ds-filter-panel input.sld').each(function(i,v){
      if($(this).is(':checked')){
        sldgang += ($(this).val()) + ',';
        summaryItems.push(new Array($(this).attr("alt"),"sld",$(this).val()));
        sldchecks++;
      }
      $('#field_sld_locality .check_count').empty().html(sldchecks);
    });
    $('.ds-filter-panel input.sex').each(function(i,v){
      if($(this).is(':checked')){
        sexgang += ($(this).val()) + ',';
        summaryItems.push(new Array($(this).attr("alt"),"sex",$(this).val()));
        sexchecks++;
      }
      $('#field_sex .check_count').empty().html(sexchecks);
    });
    $('.ds-filter-panel input.age').each(function(i,v){
      if($(this).is(':checked')){
        agegang += ($(this).val()) + ',';
        summaryItems.push(new Array($(this).attr("alt"),"age",$(this).val()));
        agechecks++;
      }
      $('#field_age_group .check_count').empty().html(agechecks);
    });
    if($('.ds-filter-panel input.ds-lhi-filter').is(':checked')){
      lhi=1;
      summaryItems.push(new Array($('.ds-filter-panel input.ds-lhi-filter').attr("alt"),"lhi",$('.ds-filter-panel input.ds-lhi-filter').val()));
    }
    if($('.ds-filter-panel input.ds-mes-filter').is(':checked')){
      mes=1;
      summaryItems.push(new Array($('.ds-filter-panel input.ds-mes-filter').attr("alt"),"mes",$('.ds-filter-panel input.ds-mes-filter').val()));
    }
    if($('.ds-filter-panel input.ds-statemap-filter').is(':checked')){
      statemap=1;
      summaryItems.push(new Array($('.ds-filter-panel input.ds-statemap-filter').attr("alt"),"statemap",$('.ds-filter-panel input.ds-statemap-filter').val()));
    }
    if($('.ds-filter-panel input.ds-hdisp-filter').is(':checked')){
      hdisp=1;
      summaryItems.push(new Array($('.ds-filter-panel input.ds-hdisp-filter').attr("alt"),"hdisp",$('.ds-filter-panel input.ds-hdisp-filter').val()));
    }
    if($('#ds-search-query-pen').attr('value').length > 0){
      searchq = $('#ds-search-query-pen').attr('value');
    }
    if($('#ds-search-objid-pen').attr('value').length > 0){
      objid = $('#ds-search-objid-pen').attr('value');
    }

    $('.ds-results-panel .ds-result').empty().html("<div style='width=100%;text-align:center;margin:10px 0 20px 0;'><img src='/sites/all/modules/custom/ch_datasearch/loader.gif'/></div>");
    $('.ds-view-header').empty();
    $('.ds-pager-desc').empty();

    var getvars = getQueryParams(document.location.search);
    var getvarnid = "";
    if(getvars.nid){
      window.location.replace(document.location.protocol +"//"+ document.location.hostname + document.location.pathname + "#objid="+getvars.nid);
    }
    if(getvars['&f[0]']){
      window.location.replace(document.location.protocol +"//"+ document.location.hostname + document.location.pathname + "#topic-area="+getvars['&f[0]'].split(":")[1]);
    }

    if(xhrTrip){
      xhr.abort();
    }
    xhrTrip=1;
    xhr = $.ajax({
            url: '/2020/data-search/Search-the-Data/results/'+pageNum+'?objid='+objid+'&ta='+tagang.slice(0,-1)+'&src='+srcgang.slice(0,-1)+'&sld='+sldgang.slice(0,-1)+'&sex='+sexgang.slice(0,-1)+'&age='+agegang.slice(0,-1)+'&lhi='+lhi+'&mes='+mes+'&statemap='+statemap+'&hdisp='+hdisp+'&srch='+searchq,
            success: function(data) {
              xhrTrip=0;
              $('.ds-results-panel .ds-result').html(data);
              Drupal.attachBehaviors($('.ds-results-panel .ds-result, .ds-search-summary'));
            }
          });

    if((searchq) || (objid) || (summaryItems.length>0)){
      var qhash='';
      var summaryGang = "<p>You searched: ";
      if(searchq){
        var cleanq=searchq.replace(/['"]+/g, '');
        summaryGang += "<span class='ds-search-tag'><span class='ds-search-tag-name'>" + searchq + "</span><span class='ds-tag-remove-search ds-pointer' value='" + cleanq + "' aria-label='Remove tag'>×</span></span>\n";
        qhash += "srch=" + searchq + ";";
      }
      if(objid){
        summaryGang += "<span class='ds-search-tag'><span class='ds-search-tag-name ds-search-tag-name-obj'>" + $('#ds-search-objid-pen').text() + "</span><span class='ds-tag-remove-objid ds-pointer' value='" + objid + "' aria-label='Remove tag'>×</span></span>\n";
        qhash += "objid=" + objid + ";";
      }
      if(summaryItems.length>0){
        jQuery.each(summaryItems, function(index, item) {
          summaryGang += "<span class='ds-search-tag'><span class='ds-search-tag-name'>" + item[0] + "</span><span class='ds-tag-remove ds-pointer' id='ds-tag-remove-" + item[2] + "' value='" + item[2] + "' type='" + item[1] + "' aria-label='Remove tag'>×</span></span>\n";
          qhash += item[1] + "=" + item[2] + ";";
        });
      }
      summaryGang += "<span class='ds-tag-remove-all'>Remove all</span></p>";
      $('.ds-search-summary').empty().html(summaryGang);
      document.location.hash = qhash;
    }else{
      $('.ds-search-summary').empty();
      document.location.hash = "";
    }

  });

  $('.clear_facet').click(function(){
    $('input.'+$(this).attr('target')).each(function(){
      $(this).prop('checked', false);
    });
    $('.ds-filter-panel input:checkbox').first().change();
  });



  $('#confidence-interval, #standard-error').change(function(){
    if($('#confidence-interval').is(':checked')){
      $('.dp-data-ci').show();
    }else{
      $('.dp-data-ci').hide();
    }
    if($('#standard-error').is(':checked')){
      $('.dp-data-se').show();
    }else{
      $('.dp-data-se').hide();
    }
    if((!$('#confidence-interval').is(':checked')) && (!$('#standard-error').is(':checked'))){
      $('.ds-data-point').css({"height":"25px"});
      $('.pophead').css({"height":"45px"});
      $('.ds-data-point').css({"line-height":"25px"});
    }else{
      $('.ds-data-point').css({"height":"40px"});
      $('.pophead').css({"height":"60px"});
      $('.ds-data-point').css({"line-height":"17px"});
    }

  });
  $('.ds-pop-check').change(function(){
    if($(this).is(':checked')){
      $('.'+$(this).attr('ds-target')).show(250);
    }else{
      $('.'+$(this).attr('ds-target')).hide(250);
    }
  });
  $('#show_all_pops').change(function(){
    $('.ds-pop-check').each(function(){
      $(this).change();
    });    
  })

  $('.ds-filter-panel input:checkbox').first().change();

  $('.ds-landing-query-search').live('click',function() {       
    $(this).attr('href', "/2020/data-search/Search-the-Data#srch=" + $('.ds-landing-query-search-input').val());
  });
  $(document).on("keypress", ".ds-landing-query-search-input", function(e) {
    if (e.which == 13) {
      $('.ds-landing-query-search')[0].click();
    }
  });

  $(".page-data-search").click(function(e)
  {
    var subject = $("#ds-topic_filter"); 
    if(e.target.id != subject.attr('id') && !subject.has(e.target).length && $(".change.open").css("display") == 'block')
    {
      $('#ds-topic_filter #ds-topic_expand',$(this).parent()).slideToggle();
      $('#ds-topic_filter .open',$(this).parent()).toggle();
      $('#ds-topic_filter .closed',$(this).parent()).toggle();
    }
  });
  $('.ds_topic_expand_button').click(function(){
    $('#ds-topic_expand',$(this).parent()).slideToggle();
    $('.open',$(this).parent()).toggle();
    $('.closed',$(this).parent()).toggle();
  });

  $('.ds-landing-topic-search').live('click',function() {    
    var topicGang='';
    $('.ds-topic-checkbox:checked').each(function(){
      topicGang += 'topic-area=' + $(this).prop('value') + ';';
    });
    $(this).attr('href', "/2020/data-search/Search-the-Data#" + topicGang);
  });

  $(".page-data-search").click(function(e)
  {
    var subject = $("#ds_src_filter"); 
    if(e.target.id != subject.attr('id') && !subject.has(e.target).length && $("#ds_src_filter .change.open").css("display") == 'block')
    {
      $('#ds_src_filter #ds-source_expand',$(this).parent()).slideToggle();
      $('#ds_src_filter .open',$(this).parent()).toggle();
      $('#ds_src_filter .closed',$(this).parent()).toggle();
    }
  });

  $('.ds_source_expand_button').click(function(){
    $('#ds-source_expand',$(this).parent()).slideToggle();
    $('.open',$(this).parent()).toggle();
    $('.closed',$(this).parent()).toggle();
  });
  $('.ds-landing-source-search').live('click',function() {    
    var sourceGang='';
    $('.ds-source-checkbox:checked').each(function(){
      sourceGang += 'source=' + $(this).prop('value') + ';';
    });
    $(this).attr('href', "/2020/data-search/Search-the-Data#" + sourceGang);
  });
});


(function ($) {
  Drupal.behaviors.ch_datasearch = {
    attach: function (context, settings) {
      $(".years-wrapper .years-button", context).click(function (e) {
        $(".years-list", $(this).parent()).slideToggle();
      });

      $(".view-population-data", context).click(function (e) {
        $('.ds-popgroup-drawer', $(this).parent().parent()).slideToggle(1000);
        $('html,body').animate({scrollTop: $('#'+$(this).attr('objid')).offset().top}, 1000);
        $(this).toggleClass('open');
        if($(this).hasClass('open')){
          $(this).empty().html("Hide data by group");
        }else{
          $(this).empty().html("View data by group");
        }
      });

      $(".ds-table-scroll-left", context).click(function (e) {
        dataTableScroll($(this), 'left');
      });
      $(".ds-table-scroll-right", context).click(function (e) {
        dataTableScroll($(this), 'right');
      });


      $('.data-table-container').each(function(){
        var locMaxScroll = 0;
        $('.ds-year', $(this)).each(function(){
          locMaxScroll += dpColWidth;
        });
        locMaxScroll -= (Math.floor(565/dpColWidth) * dpColWidth);
        $('.ds-table-right-pane', $(this)).scrollLeft(locMaxScroll);
        $('.ds-table-scroll-right', $(this)).css({'color':'#ccc'});
        if(locMaxScroll<=0){
          $('.ds-table-scroll-left', $(this)).css({'color':'#ccc'});
        }
      });

      $('.ds-tag-remove').click(function (e) {
        $('#'+$(this).attr("type")+'-'+$(this).attr("value")).prop('checked', false);
        $('.ds-filter-panel input:checkbox').first().change();
      });
      $('.ds-tag-remove-search').click(function (e) {
        $('#ds-search-query-pen').attr('value','');
        $('.ds-filter-panel input:checkbox').first().change();
      });
      $('.ds-tag-remove-objid').click(function (e) {
        $('#ds-search-objid-pen').attr('value','');
        $('.ds-filter-panel input:checkbox').first().change();
      });
      $('.ds-tag-remove-all').click(function (e) {
        $('.ds-tag-remove').each(function(){
          $('#'+$(this).attr("type")+'-'+$(this).attr("value")).prop('checked', false);
        });
        $('#ds-search-query-pen').attr('value','');
        $('#ds-search-objid-pen').attr('value','');
        $('.ds-filter-panel input:checkbox').first().change();
      });
      $('.year-checkbox', context).click(function(e){
        $('.ds-'+$(this).val(), $(this).closest('.search_container')).toggle(400);
      });

      $('.state_dropdown .selected', context).click(function(){
        $('.state_dropdown_inner', $(this).parent()).slideToggle();
      });

      $('.state_dropdown_inner .state', context).click(function(){
        if($(this).attr('data-fips') > 0){
          $('.field-hp2020-baseline, .field-hp2020-target',$(this).closest('.search_container')).css({'color':'#aaa'});
        }else{
          $('.field-hp2020-baseline, .field-hp2020-target',$(this).closest('.search_container')).css({'color':'#000'});
        }
        loadDatatable($(this).attr('objid'), $(this).attr('data-fips'));
      });

      $('.icon.term, .icon.rlog').hover(function(){
        $(this).children('.tool-tip').show();
      }, function() {
        $(this).children('.tool-tip').hide();
      });

      $('.ds-popgroup-drawer', context).each(function(){$(this).hide();});
      $('#confidence-interval').change();

      var totalReturn = parseInt($('.dp-return-count').attr('value'));
      var curOffset = parseInt($('.dp-query-offset').attr('value'));
      if(totalReturn>0){
        //$('.ds-no-results-content').empty();
        $('.ds-view-header').empty().html("<em>" + totalReturn + " Objectives</em> match your search");
        $('.ds-view-options').show();
      }else{
        $('.ds-view-header').empty().html("<div style='margin-bottom:25px;'>No results for <em>" + $('#ds-search-query-pen').attr('value') + "</em></div>");
        $('.ds-no-results-content').empty().html("<p>Data Search only looks at <strong>exact key words</strong> in objective titles. Try searching with: <ul><li>Fewer words (for example, <em>HIV</em> instead of <em>HIV prevention</em>)</li><li>Similar terms (for example, <em>child deaths</em> instead of <em>infant mortality</em>)</li><li>Less specific terms (for example, <em>high blood pressure</em> instead of <em>high blood pressure in African Americans</em>)</li></ul></p><p><strong><a href='/2020/data-search/' class='clearall'>Try another search</a>.</strong></p>");
        jQuery('.ds-view-options').hide();    
      }
      if(curOffset>-1){
        $('.ds-pager-desc').empty().html("Showing " + (curOffset+1) + " - " + Math.min(totalReturn,curOffset+10) + " of " + totalReturn + " results");
      }
      var pagerItems="";
      var curPage = parseInt($('.dp-current-page').attr('value'));
      var maxPages = Math.ceil(parseInt($('.dp-return-count').attr('value'))/10);
      if(curPage == 1){$('.ds-pager-backward-links').hide()}else{$('.ds-pager-backward-links').show();}
      if(curPage == maxPages){$('.ds-pager-forward-links').hide()}else{$('.ds-pager-forward-links').show();}
      for (i = Math.max(curPage-3,1); i <= Math.min(curPage+3,maxPages); i++) { 
        if(i==$('.dp-current-page').attr('value')){
          pagerItems += "<li class='ds-pager-item ds-pager-current ds-pointer' value='"+i+"'>"+i+"</li>";
        }else{
          pagerItems += "<li class='ds-pager-item ds-pointer' value='"+i+"'>"+i+"</li>";
        }
      }

      $('ul.ds-pager-list').empty().html(pagerItems);
      $('.ds-pager-forward-links').empty().html("<span class='ds-pager-next ds-pointer'>›</span> <span class='ds-pager-end ds-pointer'>»</span>");
      $('.ds-pager-backward-links').empty().html("<span class='ds-pager-start ds-pointer'>«</span> <span class='ds-pager-prev ds-pointer'>‹</span>");

      $('.search_container').each(function(){
        if($('.ds-popgroup-drawer .pophead', $(this)).length < 1){
          $('.view-population-data', $(this)).hide();
        }
      });
      $('.ds-pager-item').click(function(){
        pagePage($(this).val());
      });

      $('.ds-pager-next').click(function(){pagePage(curPage+1);});
      $('.ds-pager-end').click(function(){pagePage(maxPages);});
      $('.ds-pager-prev').click(function(){pagePage(curPage-1);});
      $('.ds-pager-start').click(function(){pagePage(1);});

      $('.show_footnotes, .hide_footnotes', context).click(function(){
        $('.all_footnotes', $(this).parent()).slideToggle();
        $('.hide_footnotes', $(this).parent()).toggle();
        $('.show_footnotes', $(this).parent()).toggle();
      });

      $.each(['topic-area', 'source', 'sld', 'sex','age'], function( index, type ) {
        $('.dp-filter-'+type).each(function(){
          if($(this).text()=="ALL"){
            $('input.'+type).each(function(){$(this).attr("disabled", false);});
            $('label.'+type+'-label').each(function(){
              $(this).css({'color':'#000'});
              $('.count', $(this)).empty().html($(this).attr('restore'));
            });
          }else{
            $('input.'+type).each(function(){$(this).attr("disabled", true);});
            $('label.'+type+'-label').each(function(){$(this).css({'color':'#aaa'});});
            $('label.'+type+'-label .count').each(function(){$(this).empty().html("0");});
            var ids = $(this).text().split('|');
            $.each(ids, function(i,v){
              var vid = v.split(',');
              $('#'+type+'-'+vid[0]).attr("disabled", false);
              $('#'+type+'-'+vid[0]+'-label').css({'color':'#000'});
              $('#'+type+'-'+vid[0]+'-label .count').empty().html(vid[1]);
            });
          }
        });
      });

      $.each(['topic-area', 'source', 'sld', 'sex','age'], function( index, type ) {
        $('.dp-filter-'+type+'-restore').each(function(){
          var ids = $(this).text().split('|');
          $.each(ids, function(i,v){
            var vid = v.split(',');
            $('#'+type+'-'+vid[0]+'-label').attr('restore', vid[1]);
          });
        });
      });


      $('input.ds-search_in_filter').keyup(function(){
        var search_text = $(this).val().toLowerCase();
        $(this).siblings('.pane-content').find('li.leaf label').each(function(){
          if ($(this).text().toLowerCase().indexOf(search_text) > -1) {
            $(this).parent().show();
          } else {
            $(this).parent().hide();
          }
        });
      });

      $('.ds-pop-check').each(function(){
        $(this).change();
      });


    }
  };
}(jQuery));

function loadDatatable(objid, fipscode){
  jQuery('.ds-datatable-'+objid).empty().html("<div style='width=100%;text-align:center;margin:10px 0 20px 0;'><img src='/sites/all/modules/custom/ch_datasearch/loader.gif'/></div>");
  jQuery('.ds-datatable-'+objid).load('/2020/data-search/Search-the-Data/results-datatable/'+objid+'/'+fipscode, function(){
    Drupal.attachBehaviors(jQuery('.ds-datatable-'+objid));
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      'event' : 'dataSearchDataTable',
      'formId' : objid+'/'+fipscode
    });
  });
}

function pagePage(pageNum){
  jQuery('.ds-filter-panel input:checkbox').first().trigger('change', [{'pageNum':pageNum}]);
}

function getQueryParams(qs) {
  qs = qs.split("+").join(" ");
  var params = {},
      tokens,
      re = /[?&]?([^=]+)=([^&]*)/g;
  while (tokens = re.exec(qs)) {
      params[decodeURIComponent(tokens[1])]
          = decodeURIComponent(tokens[2]);
  }
  return params;
}

function dataTableScroll(ent, dir){
  var maxScroll = 0;
  jQuery('.ds-year', jQuery(ent).parent()).each(function(){
    maxScroll += dpColWidth;
  });
  maxScroll -= (Math.floor(565/dpColWidth) * dpColWidth);

  var dOffsett = 0;
  if(dir=='left'){
    dOffsett = dpColWidth * -1;
  }else if(dir=='right'){
    dOffsett = dpColWidth;
  }

  jQuery('.ds-table-right-pane', jQuery(ent).parent()).animate(
    {
      scrollLeft: Math.min(jQuery('.ds-table-right-pane', jQuery(ent).parent()).scrollLeft()+dOffsett, maxScroll)
    }, {
      duration: 200,
      complete: function(){
        if(jQuery('.ds-table-right-pane', jQuery(ent).parent()).scrollLeft() < maxScroll){
          jQuery('.ds-table-scroll-right', jQuery(ent.parent())).css({'color':'#000'});
        }else{
          jQuery('.ds-table-scroll-right', jQuery(ent.parent())).css({'color':'#ccc'});
        }
        if(jQuery('.ds-table-right-pane', jQuery(ent).parent()).scrollLeft() > 0){
          jQuery('.ds-table-scroll-left', jQuery(ent.parent())).css({'color':'#000'});
        }else{
          jQuery('.ds-table-scroll-left', jQuery(ent.parent())).css({'color':'#ccc'});
        }
      }
    });
}

