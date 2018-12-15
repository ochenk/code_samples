<!-- ch_datasearch_results_datatable.tpl.php -->
<div class="nd_header_wrap clearfix">

<?php 

$locDropTitle = $content['displayFipsKey'];
if($locDropTitle=="United States"){
  $locDropTitle = "National Data";
}
?>

  <?php
  if(count($content['locs'])>1){
  ?>
    <div class="state_dropdown">
      <p class="selected"><img src="/sites/all/themes/odphp/images/SLD_small.png" height="18px"><?php echo $locDropTitle; ?></p>                        
      <div class="state_dropdown_inner" style="display: none;">
        <?php
        foreach($content['locs'] as $locKey=>$locData){
          print("<div class='state' data-fips='".$locData['fipscode']."' objid='". $content['obj_id'] ."'>".$locKey."</div>");
        }
        ?>
      </div>
    </div>
    <div class="sld_disclaimer"><p>Data may not be available for all states.</p></div>
  <?php
  }
  ?>  

  <div class="years-wrapper">
    <div class="years-button">Choose Years ▼</div>
    <div class="years-list" style="display: none;">
      <?php
        foreach($content['fipsyears'] as $year){
          print("<label><input type='checkbox' id='year".$year['displayyear']."' class='year-checkbox' value='".$year['displayyear']."' checked='checked' data-index='1'> ".$year['displayyear']."</label>");
        }
      ?>
    </div>
  </div>
</div>

<div class='ds-data-table-container'>

  <span class="ds-table-scroll ds-table-scroll-left">◀</span>
  <span class="ds-table-scroll ds-table-scroll-right"> ▶</span>

  <div class='ds-table-left-pane'>
    <div class='ds-table-left-pane-header headcol'><span class='ds-population-title'>Populations</span></div>
    <?php
      $line=0;
      $drawerCloseTrip=0;
      foreach($content['data'][$content['displayFipsKey']] as $poptitle=>$data){
        $poptitle = explode("|", $poptitle);
        $poptitle = $poptitle[0];

        $exclass="";
        if($data['indentlevel']==0){
          $line=0;
        }

        if(($poptitle == "Total") && ($content['displayFips']>0)){
          print("<div class='pophead ds-level0 ds-chart-line-1 ds-pop-total'><span class='ds-inner-poptitle ds-chart-title'>Total ");
          print("<a href='/2020/data/Chart/". $content['obj_id'] ."?category=1&amp;by=Total&amp;fips=". $content['displayFips'] ."' class='chart-button' target='_blank'>View Chart</a>");
          print("</span></div>");
          print("<div class='pophead ds-level1 ds-chart-line-0 ds-pop-total'><span class='ds-inner-poptitle ds-retain-header'>".$content['nationalFipsKey']."</span></div>");
          print("<div class='pophead ds-level1 ds-chart-line-1 ds-pop-total'><span class='ds-inner-poptitle ds-retain-header'>".$content['displayFipsKey']."</span></div>");
          print("<div class='ds-popgroup-drawer ds-total'>");
          $drawerCloseTrip=1;
          //print("<div class='pophead ds-level" . $data['indentlevel'] . " ds-chart-line-" . $line % 2 . " " . $exclass . "'><span class='ds-inner-poptitle'>" . $poptitle . "</span></div>");

        }else if(($poptitle == "Total") && ($content['displayFips']<1)){
          print("<div class='pophead ds-level0 ds-chart-line-1 ds-pop-total'><span class='ds-inner-poptitle ds-chart-title'>Total <a href='/2020/data/Chart/". $content['obj_id'] ."?category=1&amp;by=Total&amp;fips=". $content['displayFips'] ."' class='chart-button' target='_blank'>View Chart</a></span></div>");
          print("<div class='ds-popgroup-drawer ds-total'>");
          $drawerCloseTrip=1;
        }else{
          $dis="";
          if(($data['disparities']) && ($content['displayFips']<0)){
            $dis = "<a href='/2020/data/disparities/summary/Chart/".$content['obj_id']."/".$data['category']."' class='disparities-button spotlight' target='_blank'>View Disparities</a>";
          }
          if($data['indentlevel']==0){
            $fnnums='';
            if($content['footnotes']['popgroup']['pop-'.$data['category']]){
              foreach($content['footnotes']['popgroup']['pop-'.$data['category']] as $fn){
                $fnnums .= $fn['fnnumber'] . ",";
              }
            }
            $poptitleCarryover = strtolower(str_replace(str_split(' /()'),'_',$poptitle));
            if(strpos($poptitleCarryover, 'disability_status') !== FALSE){
              $poptitleCarryover = 'disability_status';
            }else if(strpos($poptitleCarryover, 'race_ethnicity') !== FALSE){
              $poptitleCarryover = 'race_ethnicity';
            }else if(strpos($poptitleCarryover, 'age_group') !== FALSE){
              $poptitleCarryover = 'age_group';
            }else if(strpos($poptitleCarryover, 'sexual_orientation') !== FALSE){
              $poptitleCarryover = 'sexual_orientation';
            }else if(strpos($poptitleCarryover, 'sex') !== FALSE){
              $poptitleCarryover = 'sex';
            }else if(strpos($poptitleCarryover, 'educational_attainment') !== FALSE){
              $poptitleCarryover = 'educational_attainment';
            }else if(strpos($poptitleCarryover, 'income') !== FALSE){
              $poptitleCarryover = 'income';
            }else if(strpos($poptitleCarryover, 'family_type') !== FALSE){
              $poptitleCarryover = 'family_type';
            }else if(strpos($poptitleCarryover, 'country_of_birth') !== FALSE){
              $poptitleCarryover = 'country_of_birth';
            }else if(strpos($poptitleCarryover, 'geographic_location') !== FALSE){
              $poptitleCarryover = 'geographic_location';
            }else if(strpos($poptitleCarryover, 'health_insurance_status') !== FALSE){
              $poptitleCarryover = 'health_insurance_status';
            }else if(strpos($poptitleCarryover, 'marital_status') !== FALSE){
              $poptitleCarryover = 'marital_status';
            }else if(strpos($poptitleCarryover, 'gender_identity') !== FALSE){
              $poptitleCarryover = 'gender_identity';
            }else if(strpos($poptitleCarryover, 'school') !== FALSE){
              $poptitleCarryover = 'school';
            }else{
              $poptitleCarryover = 'other';
            }
            print("<div class='pophead ds-level" . $data['indentlevel'] . " ds-chart-line-" . $line % 2 . " " . $exclass . " ds-pop-" . $poptitleCarryover . "'><span class='ds-inner-poptitle ds-chart-title'>" . $poptitle . " <sup>" . rtrim($fnnums,','). "</sup>" . " ");
            if($data['chart']){
              print("<a href='/2020/data/Chart/". $content['obj_id'] ."?category=" . $data['category'] . "&amp;by=". $poptitle ."&amp;fips=". $content['displayFips'] ."' class='chart-button' target='_blank'>View Chart</a>");
            }
            print($dis."</span></div>");
          }else{
            print("<div class='pophead ds-level" . $data['indentlevel'] . " ds-chart-line-" . $line % 2 . " " . $exclass . " ds-pop-" . $poptitleCarryover . "'><span class='ds-inner-poptitle'>" . $poptitle . "</span></div>");
          }
        }

        $line++;
      }
      if($drawerCloseTrip){
        print("</div>");
      }
    ?>

  </div>

  <div class='ds-table-right-pane'>
    <div class='ds-table-right-pane-slider'>
      <div class='ds-table-right-pane-slider-header clearfix'>

        <?php
          foreach($content['fipsyears'] as $year){
            print("<span class='ds-year ds-" . $year['displayyear'] . " headcol'>" . $year['displayyear']);
            $fngang='';
            foreach($content['footnotes'][$year['endyear']] as $fn){
              $fngang .= $fn['fnnumber'].",";
            }
            print(" <sup>".rtrim($fngang,',')."</sup>");
            print("</span>");
          }
        ?>
      </div>

      <div>
        <?php
        $line2=0;
        //var_dump($content['data'][$content['displayFipsKey']]);
        foreach($content['data'][$content['displayFipsKey']] as $poptitle=>$popdata){
          $poptitle = explode("|", $poptitle);
          $poptitle = $poptitle[0];
          if($popdata['indentlevel'] == 0){$line2=0;}

          if(($poptitle == "Total") && ($content['displayFips']>0)){
            print("<div class='clearfix  ds-level0 ds-pop-total'><span class='ds-data-point'></span></div>");

            print("<div class='clearfix ds-chart-line-0 ds-national-total ds-level1 ds-pop-total'>");
            foreach($content['fipsyears'] as $year){
              $datapoint = $content['data'][$content['nationalFipsKey']]['Total|0'][$year['displayyear']];
              print("<span class='ds-data-point ds-".$year['displayyear']."'>");
              if($datapoint['estimate']){print("<span class='dp-data-estimate'>" . trim($datapoint['estimate']) . "</span>");}
              if($datapoint['lowerci']>0){print("<span class='grey-text dp-data-ci'>CI " . trim($datapoint['lowerci']) . " / " . trim($datapoint['upperci']) . "</span>");}
              if($datapoint['stderr']>0){print("<span class='grey-text dp-data-se'>SE " . trim($datapoint['stderr']) . "</span>");}
              print("</span>");
            }
            print("</div>");

            print("<div class='clearfix  ds-level1 ds-pop-total'>");
            foreach($content['fipsyears'] as $year){
              $datapoint = $popdata[$year['displayyear']];
              print("<span class='ds-data-point ds-".$year['displayyear']."'>");
              if($datapoint['estimate']){print("<span class='dp-data-estimate'>" . trim($datapoint['estimate']) . "</span>");}
              if($datapoint['lowerci']>0){print("<span class='grey-text dp-data-ci'>CI " . trim($datapoint['lowerci']) . " / " . trim($datapoint['upperci']) . "</span>");}
              if($datapoint['stderr']>0){print("<span class='grey-text dp-data-se'>SE " . trim($datapoint['stderr']) . "</span>");}
              print("</span>");
            }
            print("</div>");

            print("</div><div class='ds-popgroup-drawer'>");
            //$line++;

          }else if(($poptitle == "Total") && ($content['displayFips']<1)){
            print("<div class='clearfix  ds-level0 ds-pop-total'>");
            foreach($content['fipsyears'] as $year){
              $datapoint = $popdata[$year['displayyear']];
              print("<span class='ds-data-point ds-".$year['displayyear']."'>");
              if($datapoint['estimate']){print("<span class='dp-data-estimate'>" . trim($datapoint['estimate']) . "</span>");}
              if($datapoint['lowerci']>0){print("<span class='grey-text dp-data-ci'>CI " . trim($datapoint['lowerci']) . " / " . trim($datapoint['upperci']) . "</span>");}
              if($datapoint['stderr']>0){print("<span class='grey-text dp-data-se'>SE " . trim($datapoint['stderr']) . "</span>");}
              print("</span>");
            }
            print("</div>");
            print("</div><div class='ds-popgroup-drawer'>");

          }else{
            $zebra="";
            if($popdata['indentlevel'] == 0){
              $poptitleCarryover = strtolower(str_replace(str_split(' /()'),'_',$poptitle));
              if(strpos($poptitleCarryover, 'disability_status') !== FALSE){
                $poptitleCarryover = 'disability_status';
              }else if(strpos($poptitleCarryover, 'race_ethnicity') !== FALSE){
                $poptitleCarryover = 'race_ethnicity';
              }else if(strpos($poptitleCarryover, 'age_group') !== FALSE){
                $poptitleCarryover = 'age_group';
              }else if(strpos($poptitleCarryover, 'sexual_orientation') !== FALSE){
                $poptitleCarryover = 'sexual_orientation';
              }else if(strpos($poptitleCarryover, 'sex') !== FALSE){
                $poptitleCarryover = 'sex';
              }else if(strpos($poptitleCarryover, 'educational_attainment') !== FALSE){
                $poptitleCarryover = 'educational_attainment';
              }else if(strpos($poptitleCarryover, 'income') !== FALSE){
                $poptitleCarryover = 'income';
              }else if(strpos($poptitleCarryover, 'family_type') !== FALSE){
                $poptitleCarryover = 'family_type';
              }else if(strpos($poptitleCarryover, 'country_of_birth') !== FALSE){
                $poptitleCarryover = 'country_of_birth';
              }else if(strpos($poptitleCarryover, 'geographic_location') !== FALSE){
                $poptitleCarryover = 'geographic_location';
              }else if(strpos($poptitleCarryover, 'health_insurance_status') !== FALSE){
                $poptitleCarryover = 'health_insurance_status';
              }else if(strpos($poptitleCarryover, 'marital_status') !== FALSE){
                $poptitleCarryover = 'marital_status';
              }else if(strpos($poptitleCarryover, 'gender_identity') !== FALSE){
                $poptitleCarryover = 'gender_identity';
              }else if(strpos($poptitleCarryover, 'school') !== FALSE){
                $poptitleCarryover = 'school';
              }else{
                $poptitleCarryover = 'other';
              }
            }


            $zebra = 'ds-chart-line-' . ($line2 % 2);
            print("<div class='clearfix ".$zebra." ds-level" . $popdata['indentlevel'] ." ds-pop-" . $poptitleCarryover . "'>");
            foreach($content['fipsyears'] as $year){
              $datapoint = $popdata[$year['displayyear']];
              print("<span class='ds-data-point ds-".$year['displayyear']."'>");
              if($datapoint['estimate']){print("<span class='dp-data-estimate'>" . trim($datapoint['estimate']) . "</span>");}
              if($datapoint['lowerci']>0){print("<span class='grey-text dp-data-ci'>CI " . trim($datapoint['lowerci']) . " / " . trim($datapoint['upperci']) . "</span>");}
              if($datapoint['stderr']>0){print("<span class='grey-text dp-data-se'>SE " . trim($datapoint['stderr']) . "</span>");}
              print("</span>");
            }
            print("</div>");
            $line2++;
          }
        }
      ?>
        </div>
      </div>
    </div>


  </div>

</div>
<div class="ds-view-subgroup-control">
  <button class="view-population-data" objid='<?php echo $content['obj_id']; ?>'>View data by group</button>
</div>
