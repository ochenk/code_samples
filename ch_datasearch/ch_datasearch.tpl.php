<!-- ch_datasearch.tpl.php -->

<div class="panel-display panel-2col clearfix">
  <div class="panel-panel ds-filter-panel panel-col-first">
    <div class="inside">
      <div class="panel-pane pane-custom pane-1">
	    <div class="pane-content">
	      <h2 class="filters_header">Narrow Your Search</h2>
	    </div>
	  </div>

		<!-- topics -->
		<div class="panel-separator"></div>
		<div class="panel-pane pane-block " id="field_topic_area">
		  <h2 class="pane_expand_trigger">Topic Areas <span>(<span class="check_count">0</span>&nbsp;Selected)</span></h2>
		  <div class="filter_contents" style="display:none;">
		    <label style="display:none;" for="search_filter_Topic_Areas">Search Within Topic Areas</label>
		    <input id="search_filter_Topic_Areas" class="ds-search_in_filter" type="text" placeholder="Search Within Topic Areas">
		    <div class="pane-content">
		      <div class="item-list">
		        <ul class="facetapi-facetapi-checkbox-links facetapi-facet-field-topic-area" id="facetapi-facet-search-apinew-node-index-block-field-topic-area">

			        <?php foreach($content['filters']['topics'] as $topic){ 
								print "<li class='leaf'><input type='checkbox' name='topic-areas[]' id='topic-area-" . $topic['id'] . "' value='" . $topic['id'] . "' alt=\"" . $topic['title'] . "\" class='topic-area'/> <label id='topic-area-" . $topic['id'] . "-label' for='topic-area-" . $topic['id'] . "' class='topic-area-label'>" . $topic['title'] . " (<span class='count'>" . $topic['objcount'] . "</span>)</label><span class='element-invisible'>" . $topic['title'] . " filter </span></li>";
			        } ?>

		        </ul>
		      </div>
		    </div>
		    <div class="clear_container"><span class="clear_facet" target='topic-area'><img alt=" " src="/sites/all/themes/odphp/images/temp_x.png">Clear Selection(s)</span></div>
			</div>
		</div>


		<!-- sources -->
		<div class="panel-separator"></div>
		<div class="panel-pane pane-block pane-facetapi-3di4xc11ropceiefsdruzd1epe1qbcpf " id="field_data_sources">
		  <h2 class="pane_expand_trigger">Data Sources <span>(<span class="check_count">0</span>&nbsp;Selected)</span></h2>
		  <div class="filter_contents" style="display:none;">
		    <label style="display:none;" for="search_filter_Data_Sources">Search Within Data Sources</label>
		    <input id="search_filter_Data_Sources" class="ds-search_in_filter" type="text" placeholder="Search Within Data Sources">
		    <div class="pane-content">
		      <div class="item-list">
		        <ul class="facetapi-facetapi-checkbox-links facetapi-facet-field-data-sources" id="facetapi-facet-search-apinew-node-index-block-field-data-sources">
		          
			        <?php foreach($content['filters']['sources'] as $source){ 
								print "<li class='leaf'><input type='checkbox' name='sources[]' id='source-" . $source['id'] . "' value='" . $source['id'] . "' alt='".$source['title']."' class='source'/> <label id='source-" . $source['id'] . "-label' for='source-" . $source['id'] . "' class='source-label'>" . $source['title'] . " (<span class='count'>" . $source['objcount'] . "</span>)</label><span class='element-invisible'>" . $source['title'] . " filter </span></li>";
			        } ?>
		        </ul>
		      </div>
		    </div>
		    <div class="clear_container"><span class="clear_facet" target='source'><img alt=" " src="https://www.healthypeople.gov/sites/all/themes/odphp/images/temp_x.png">Clear Selection(s)</span></div>
	  	</div>
		</div>



		<!-- state level data -->
		<div class="panel-separator"></div>
		<div class="panel-pane pane-block pane-facetapi-puu6v9gsyblmktcygoyfcnqqoew7cysu " id="field_sld_locality">
		  <h2 class="pane_expand_trigger">State-Level Data Available <span>(<span class="check_count">0</span>&nbsp;Selected)</span></h2>
		  <div class="filter_contents" style="display:none;">
		    <div class="pane-content">
		      <div class="item-list">
		        <ul class="facetapi-facetapi-checkbox-links facetapi-facet-field-sld-locality" id="facetapi-facet-search-apinew-node-index-block-field-sld-locality">
			        <?php foreach($content['filters']['sld'] as $sld){ 
								print "<li class='leaf'><input type='checkbox' name='sld[]' id='sld-" . $sld['fipscode'] . "' value='" . $sld['fipscode'] . "' alt='".$sld['title']."' class='sld'/> <label id='sld-" . $sld['fipscode'] . "-label' for='sld-" . $sld['fipscode'] . "' class='sld-label'>" . $sld['title'] . " (<span class='count'>" . $sld['objcount'] . "</span>)</label><span class='element-invisible'>" . $sld['title'] . " filter </span></li>";
			        } ?>
		        </ul>
		      </div>
		    </div>
		    <div class="clear_container"><span class="clear_facet" target='sld'><img alt=" " src="https://www.healthypeople.gov/sites/all/themes/odphp/images/temp_x.png">Clear Selection(s)</span></div>
		  </div>
		</div>



		<!-- sex -->
		<div class="panel-separator"></div>
		<div class="panel-pane pane-block pane-facetapi-1sqtkyy1qd7rxojwwx2ago4q0aw5okkp " id="field_obj_sex">
		  <h2 class="pane_expand_trigger">Sex-Specific Objectives <span>(<span class="check_count">0</span>&nbsp;Selected)</span></h2>
		  <div class="filter_contents" style="display:none;">
		    <div class="pane-content">
		      <div class="item-list">
		        <ul class="facetapi-facetapi-checkbox-links facetapi-facet-field-obj-sex facetapi-processed" id="facetapi-facet-search-apinew-node-index-block-field-obj-sex">
		          <li class="leaf"><input type="checkbox" name='sex[]' id='sex-543' value='543' alt='Female' class='sex'><label id='sex-543-label' class='sex-label' for="sex-543"> Female (<span class='count'><?php echo $content['filters']['sex']['female']; ?></span>) </label><span class="element-invisible">Female filter </span></li>
		          <li class="leaf"><input type="checkbox" name='sex[]' id='sex-542' value='542' alt='Male' class='sex'><label id='sex-542-label' class='sex-label' for="sex-542"> Male (<span class='count'><?php echo $content['filters']['sex']['male']; ?></span>) </label><span class="element-invisible">Male filter </span></li>
		        </ul>
		      </div>
		    </div>
		    <div class="clear_container"><span class="clear_facet" target='sex'><img alt=" " src="https://www.healthypeople.gov/sites/all/themes/odphp/images/temp_x.png">Clear Selection(s)</span></div>
		  </div>
		</div>


		<!-- age -->
		<div class="panel-separator"></div>
		<div class="panel-pane pane-block pane-facetapi-rilhwydlypxgizknoofil17ghbulwx1k " id="field_age_group">
		  <h2 class="pane_expand_trigger">Age-Specific Objectives <span>(<span class="check_count">0</span>&nbsp;Selected)</span></h2>
		  <div class="filter_contents" style="display:none;">
		    <div class="pane-content">
		      <div class="item-list">
		        <ul class="facetapi-facetapi-checkbox-links facetapi-facet-field-obj-age-group facetapi-processed" id="facetapi-facet-search-apinew-node-index-block-field-obj-age-group">
			        <?php foreach($content['filters']['age'] as $age){ 
								print "<li class='leaf'><input type='checkbox' name='age[]' id='age-" . $age['id'] . "' value='" . $age['id'] . "' alt='".$age['name']."' class='age'/> <label for='age-" . $age['id'] . "' id='age-" . $age['id'] . "-label' class='age-label'>" . $age['name'] . " (<span class='count'>" . $age['objcount'] . "</span>)</label><span class='element-invisible'>" . $age['name'] . " filter </span></li>";
			        } ?>
		        </ul>
		      </div>
		    </div>
		    <div class="clear_container"><span class="clear_facet" target='age'><img alt=" " src="https://www.healthypeople.gov/sites/all/themes/odphp/images/temp_x.png">Clear Selection(s)</span></div>
		  </div>
		</div>
		
		<!-- state maps -->
		<div class="panel-separator"></div>
		<div class="panel-pane pane-block pane-facetapi-etsrrwj1lry0ncraducyz178jpt8yva9 search-terms-facet">
		  <div class="filter_contents">
		    <div class="pane-content">
		      <div class="item-list">
		        <ul class="facetapi-facetapi-checkbox-links facetapi-facet-field-odphp-terms" id="facetapi-facet-search-apinew-node-index-block-field-odphp-terms">
		          <li class="leaf"><input type="checkbox" id='statemap-1' class="ds-statemap-filter" alt='State Map Available' value='1'><label class="ds-statemap-filter-label" for="statemap-1">State Map Available</label></li>
		        </ul>
		      </div>
		    </div>
		  </div>
		</div>

		<!-- Health Disparities -->
		<div class="panel-separator"></div>
		<div class="panel-pane pane-block pane-facetapi-etsrrwj1lry0ncraducyz178jpt8yva9 search-terms-facet">
		  <div class="filter_contents">
		    <div class="pane-content">
		      <div class="item-list">
		        <ul class="facetapi-facetapi-checkbox-links facetapi-facet-field-odphp-terms" id="facetapi-facet-search-apinew-node-index-block-field-odphp-terms">
		          <li class="leaf"><input type="checkbox" id='hdisp-1' class="ds-hdisp-filter" alt='Health Disparities Data Available' value='1'><label class="ds-hdisp-filter-label" for="hdisp-1">Health Disparities Data Available</label></li>
		        </ul>
		      </div>
		    </div>
		  </div>
		</div>

		<!-- lhi -->
		<div class="panel-separator"></div>
		<div class="panel-pane pane-block pane-facetapi-etsrrwj1lry0ncraducyz178jpt8yva9 search-terms-facet">
		  <div class="filter_contents">
		    <div class="pane-content">
		      <div class="item-list">
		        <ul class="facetapi-facetapi-checkbox-links facetapi-facet-field-odphp-terms" id="facetapi-facet-search-apinew-node-index-block-field-odphp-terms">
		          <li class="leaf"><input type="checkbox" id='lhi-1' class="ds-lhi-filter" alt='LHI' value='1'><label class="ds-lhi-filter-label" for="lhi-1">LHI</label></li>
		        </ul>
		      </div>
		    </div>
		  </div>
		</div>

		<!-- measurable -->
		<div class="panel-separator"></div>
		<div class="panel-pane pane-block pane-facetapi-etsrrwj1lry0ncraducyz178jpt8yva9 search-terms-facet">
		  <div class="filter_contents">
		    <div class="pane-content">
		      <div class="item-list">
		        <ul class="facetapi-facetapi-checkbox-links facetapi-facet-field-odphp-terms" id="facetapi-facet-search-apinew-node-index-block-field-odphp-terms">
		          <li class="leaf"><input type="checkbox" id='mes-1' class="ds-mes-filter" alt='Measurable Objectives' value='1'><label class="ds-mes-filter-label" for="mes-1">Measurable Objectives</label></li>
		        </ul>
		      </div>
		    </div>
		  </div>
		</div>



    </div>
  </div>
  <div class="panel-panel ds-results-panel panel-col-last">
    <div class="inside">
      <div class="ds-search-header">
        <p>
          <label for="ds-keyword-search">Search</label>
          <input type="text" name="keyword" class="ds-input ds-input-text" id="ds-keyword-search"> <span class="ds-input ds-search-button ds-pointer">Search</span>
          <span id='ds-search-query-pen' style='display:none;' value=''></span>
          <span id='ds-search-objid-pen' style='display:none;' value=''></span>
				</p>
      </div>
      <div class="ds-search-summary"></div>
      <div class="panel-separator"></div>
      <div class="panel-pane pane-views-panes pane-objective-faceted-search-panel-pane-1">
        <div class="view view-objective-faceted-search view-id-objective_faceted_search view-display-id-panel_pane_1 view-dom-id-df6916d7d71e0e065a17541fc6475028">
          <div class="ds-results-header clearfix">
            <div class="view-header ds-view-header"></div>
            <div class="ds-view-options">
              <p>
                <label>
                  <input type="checkbox" name="confidence-interval" id="confidence-interval" value="show-confidence-interval"> Show Confidence Interval (if available)</label>
                <br>
                <label>
                  <input type="checkbox" name="standard-error" id="standard-error" value="show-standard-error"> Show Standard Error (if available)</label>
              </p>
            </div>
          </div>
          <div class='ds-no-results-content'></div>
          <div class="view-content">
            <div class="ds-result">
            	<!-- dynamic content here -->
            </div>
          </div>

          <div id="view-pager" class="pager-wrapper ds-pager clearfix">
            <div class="view-footer ds-pager-desc"></div>
            <div class="pager clearfix">
              <ul class="links pager-links ds-pager-backward-links"></ul>
              <ul class="links pager-list ds-pager-list"></ul>
              <ul class="links pager-links ds-pager-forward-links"></ul>
            </div>
          </div>

          <div id='ds-display-populations'>
						<div class="customize-display-dropdown clearfix">
						  <div class="show_all_wrapper">
						    <input id="show_all_pops" value="show_all" type="checkbox" checked="checked"><label id="show_all_label" for="show_all_pops">Display All Populations</label>
						  </div>
						  <div class="clearfix">
						    <div class="col-3">
						      <ul class="list-unstyled">
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-total' name="Total" value="Total"> Total</label></li>
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-sex' name="Sex" value="Sex"> Sex</label></li>
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-race_ethnicity' name="Race/Ethnicity" value="Race/Ethnicity"> Race/Ethnicity</label></li>
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-age_group' name="Age group" value="Age group"> Age Group</label></li>
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-educational_attainment' name="Educational attainment" value="Educational attainment"> Educational attainment</label></li>
						      </ul>
						    </div>
						    <div class="col-3">
						      <ul class="list-unstyled">
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-income' name="Family income" value="Family income"> Family income</label></li>
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-family_type' name="Family type" value="Family type"> Family type</label></li>
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-country_of_birth' name="Country of birth" value="Country of birth"> Country of birth</label></li>
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-disability_status' name="Disability status" value="Disability status"> Disability status</label></li>
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-geographic_location' name="Geographic location" value="Geographic location"> Geographic location</label></li>
						      </ul>
						    </div>
						    <div class="col-3">
						      <ul class="list-unstyled">
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-health_insurance_status' name="Health insurance status" value="Health insurance status"> Health insurance status</label></li>
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-sexual_orientation' name="Sexual orientation" value="Sexual orientation"> Sexual orientation</label></li>
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-marital_status' name="Marital status" value="Marital status"> Marital status</label></li>
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-school' name="Student/school characteristics" value="Student/school characteristics"> Student/school characteristics</label></li>
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-gender_identity' name="Gender identity" value="Gender identity"> Gender identity</label></li>
						        <li><label><input checked="checked" type="checkbox" class='ds-pop-check' ds-target='ds-pop-other' name="All other populations" value="All other populations"> All other populations</label></li>
						      </ul>
						    </div>

						  </div>
						</div>
						<div class="ds-display-prefs">
						  <button class="ds-display-prefs-drawer">Set Display Preferences</button>
						</div>						
          </div>



        </div>
      </div>
    </div>

  </div>
</div>