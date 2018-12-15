<!-- ch_datasearch_results.tpl.php -->
<h3 class="topic-header"><?php echo $content['topic_title'] ?></h3>
<div class="data-table data-table-container objective-<?php echo $content['obj_id'] ?> clearfix">
  <div class="views-row">
    <div class="objective-title ">
      <a id="<?php echo $content['obj_id'] ?>" href="/2020/topics-objectives/topic/<?php echo $content['topic_title_despaced'] ?>/objectives#<?php echo $content['obj_id'] ?>"><span class="views-field-title"><span class="field-content"><?php echo $content['obj_title'] ?></span></span>
      <span class="views-field-field-long-title"><span class="field-content"><?php echo $content['obj_long_title'] ?></span></span></a>
      <div class="icons">
        
        <?php if($content['is_lhi']>0){ ?>
          <div class="icon term tid-1154">LHI
            <div class="tool-tip">
              <p>Leading Health Indicators are a subset of Healthy People 2020 objectives selected to communicate high-priority health issues.</p>
              <b class="border-notch notch"></b><b class="notch"></b>
            </div>
          </div>
        <?php } ?>
        <?php if($content['revision_code'] == 2){ ?>
          <div class="icon rlog rlid-773 disposition-code-2"><a href="/node/<?php echo $content['obj_id'] ?>/data_details#revision_history_header" class="iconlink" target="_blank">Revised</a><div class="tool-tip" style="display: none;"><p>This objective was&nbsp;<strong>revised</strong>. See Revision History for Details.</p><b class="border-notch notch"></b><b class="notch"></b></div></div>
        <?php } ?>
        <?php if($content['revision_code'] == 3){ ?>
          <div class="icon rlog rlid-465 disposition-code-3"><a href="/node/<?php echo $content['obj_id'] ?>/data_details#revision_history_header" class="iconlink" target="_blank">Archived</a><div class="tool-tip" style="display: none;"><p>This objective was&nbsp;<strong>archived</strong>&nbsp;due to lack of data source, changes in science, or was replaced with other objectives. See Revision History for Details.</p><b class="border-notch notch"></b><b class="notch"></b></div></div>
        <?php } ?>
        <?php if($content['revision_code'] == 1){ ?>
          <div class="icon rlog rlid-743 disposition-code-1">New<div class="tool-tip" style="display: none;"><p>The objective was&nbsp;<strong>added</strong>&nbsp;within the last year. See Revision History for Details.</p><b class="border-notch notch"></b><b class="notch"></b></div></div>
        <?php } ?>


      </div>
    </div>
    <div class="views-field views-field-field-full-description"> <span class="field-content"><?php echo $content['full_description'] ?></span> </div>
    <div class="views-field views-field-nid">
      
      <?php if($content['has_2020_data']){ ?>


      <div class="ds-block-field-content">
        <div class="search_container">
          <div class="top_obj_data">
            <div class="field-hp2020-baseline">
              <span class="label label-field-hp2020-baseline">2020 Baseline (year): </span>
              <span class="field-content"><?php echo $content['baseline'] ?> (<?php echo $content['baseline_year'] ?>)</span>
            </div>
            <div class="field-hp2020-target">
              <span class="label label-field-hp2020-target">2020 Target: </span>
              <span class="field-content"><?php echo $content['target'] ?>
                <?php
                  if($content['footnotes']['target']){
                    $fnnums='';
                    foreach($content['footnotes']['target'] as $fn){
                      $fnnums .= $fn['fnnumber'] . ',';
                    }
                    print("<sup>" . rtrim($fnnums,',') . "</sup>");
                  }
                  ?>
              </span>
            </div>
            <div class="field-desired-direction">
              <span class="label label-field-desired-direction">Desired Direction: </span>
              <span class="field-content <?php echo $content['direction_despaced'] ?>"><?php echo $content['direction'] ?></span>
            </div>
          </div>
          

<script>
jQuery(document).ready(function($){
  loadDatatable('<?php echo $content['obj_id'] ?>', '-1');
});
</script>

          <div class='ds-datatable-<?php echo $content['obj_id'] ?>'></div>


        </div>

        <?php if($content['sources']){ ?>
        <div class="objective-data data-sources-field">
          <span class="label label-field-data-sources-label">Data Source: </span>
          <div class="ds-block-footer-field-content clearfix"><?php echo $content['sources'] ?></div>
        </div>
        <?php } ?>
        <div class="objective-data data-field">
          <span class="label">Data:</span>
          <div class="ds-block-footer-field-content clearfix">

            <?php if($content['cyear']){ ?>
              <div class="clearfix map-link">
                <a href="/2020/data/map/<?php echo $content['obj_id'] ?>?year=<?php echo $content['cyear'] ?>" target='_blank'>
                  <img src="/sites/all/themes/odphp/images/SLD_small.png" alt="State Level Map Data">Map of state-level data for this objective
                </a>
              </div>
            <?php } ?>

            <div class="clearfix data-details">
              <a target="_blank" href="/node/<?php echo $content['obj_id'] ?>/data_details" target='_blank'><img alt="Data Details" class="tech_specs_icon" src="https://www.healthypeople.gov/sites/all/themes/odphp/images/icon-data-details.png">Learn more about the methodology and measurement of this HP2020 objective</a>
            </div>
            <div class="clearfix download">
              <a href="/2020/data-search/download/<?php echo $content['obj_id'] ?>"><img alt="Download Data" class="tech_specs_icon" src="https://www.healthypeople.gov/sites/all/themes/odphp/images/download_btn.png">Download all data for this HP2020 objective [XLS - <?php echo $content['filesize'] ?>] </a>
            </div>
          </div>
        </div>


        <?php if($content['revised']>0){ ?>
        <div class="objective-data field-obj-revision-log">
          <span class="label">Revision History:</span>
          <span class="field-content">
            <div class="rlog-info-content">
              <div><div class="rlog-why disposition-2">This objective was revised. Read more about the <a href="/node/<?php echo $content['obj_id'] ?>/data_details#revision_history_header">revision history</a>.</div></div>
            </div>
          </span>
        </div>
        <?php } ?>


        <div class="objective-data footnotes-field">
          <span class="label">Footnotes: </span>
          <div class="ds-block-footer-field-content">
            <div class="objective_footnotes">
              <?php foreach($content['footnotes'] as $fn){
                print("<div class='objective_footnote'><span class='sup'>".$fn['number']."</span> ".$fn['text']."</div>");
              }?>
            </div>
            <a class="show_footnotes">View All Footnotes</a>
            <a class="hide_footnotes" style="display: none;">Hide All Footnotes</a>
            <div class="all_footnotes" style="display: none;">

              <?php //if($content['dsu_flag']){ ?>
                <span class="DSU_footnote">DSU: Data do not meet the criteria for statistical reliability, data quality, or confidentiality.<br /></span>
              <?php //} ?>
              <?php //if($content['dnc_flag']){ ?>
                <span class="DNC_footnote">DNC: Data for specific population not collected.<br /></span>
              <?php //} ?>
              <?php //if($content['dna_flag']){ ?>
                <span class="DNA_footnote">---: Data are not available.<br /></span>
              <?php //} ?>
              <span>† Target is not applicable for this population group.<br /></span>
              <span>§ This population group is not age adjusted.<br /></span>
              <span>Data are subject to revision and may have changed since a previous release.<br /></span>
              <span>Unless noted otherwise, any age-adjusted data are adjusted using the year 2000 standard population.<br /></span>
              <span>Data are not available or not collected for populations not shown.<br /></span>
              <span class="CI_footnote">CI: 95% confidence interval.</span>
              <span>Where applicable, the term “All Reporting Areas” is defined in the Data Details.</span>

            </div>
          </div>
        </div>
      </div>

    <?php }else{ ?>

      <span class="field-content">
        <div class="search_container-<?php echo $content['obj_id'] ?>">
          <div class="data-table nd_table js-init-scrolling-table clearfix">
            <div class="table-wrap">
              <h3 class='ds-no-data'>Data Not Available</h3>
            </div>
          </div>
          <div class="objective-data data-sources-field">
            <span class="label label-field-data-sources-label"> Potential Data Source: </span>
            <span class="field-content"><?php if($content['sources']){echo $content['sources'];}else{echo "To be determined";} ?></span>
          </div>
        </div>
      </span>      

    <?php } ?>


    </div>
  </div>
</div>