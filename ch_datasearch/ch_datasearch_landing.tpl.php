<div class="ds-landing">
  <h2 class="ds-title">DATA2020</h2>
  <p class="ds-prose">View data for:
    <span class="ds-landing-icon ds-icon-objectives">HP2020 objectives</span>
    <span class="ds-landing-icon ds-icon-levels">National and State levels</span>
    <span class="ds-landing-icon ds-icon-disparities">Disparities</span>
    <a href="https://www.healthypeople.gov/2020/data-search/About-the-Data" class="ds-landing-about">About the data</a>
  </p>
  <div class="ds-block-outer">
    <div class="ds-title-block clearfix">
      <h3 class="ds-title ds-title-tight">Search by:</h3>
    </div>
    <div class="ds-block clearfix">
      <div class="ds-option clearfix">
        <p class="ds-prose">
          <label for="ds-keyword-search"><strong>Key Words</strong></label>
        </p>

        <div class="ds-col">
          <input type="text" name="keyword" class="ds-input ds-input-text ds-landing-query-search-input" id="ds-keyword-search">
        </div>
        <div class="ds-col">
          <a href="/2020/data-search/Search-the-Data" class="ds-input ds-search-button ds-landing-query-search">Search</a>
        </div>
        <p class="ds-instruction">For example: try searching “smoking” or “insurance” to find matching objectives.</p>
      </div>
    </div>

    <div class="ds-block clearfix">
      <div class="ds-option clearfix">
        <p class="ds-prose"><strong>Topic Area</strong></p>
        <div class="ds-col">
          <div class="ds_filter_container">
            <div class="ds_filter_container_inner">
              <div id="ds-topic_filter" class="ds_filter">
                <div class="ds_topic_expand_button">
                  <div class="change closed">Select one or more Topic Areas</div>
                  <div class="change open" style="display:none;">Select the Topic Area(s) you want to view:</div>
                </div>
                <div id="ds-topic_expand" style="display:none">
                  <ul class="ds_filter_list">
                    <?php
                    foreach($content['filters']['topics'] as $topic){
                      print("<li>
                        <input type='checkbox' id='landing-".$topic['id']."' class='ds-topic-checkbox' value='".$topic['id']."'>
                        <label for='landing-".$topic['id']."'>".$topic['title']."</label>
                      </li>");
                    }
                    ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="ds-col">
          <a href="/2020/data-search/Search-the-Data" class="ds-input ds-search-button ds-landing-topic-search">Search</a>
        </div>
      </div>
    </div>

    <div class="ds-block clearfix">
      <div class="ds-option clearfix">
        <p class="ds-prose"><strong>Data Source</strong></p>
        <div class="ds-col">
          <div class="ds_filter_container">
            <div class="ds_filter_container_inner">
              <div id="ds_src_filter" class="ds_filter">
                <div class="ds_source_expand_button">
                  <div class="change closed">Select one or more Data Sources</div>
                  <div class="change open" style="display:none;">Select the Data Source(s) you want to view:</div>
                </div>
                <div id="ds-source_expand" style="display:none">
                  <ul class="ds_filter_list">
                    <?php
                    foreach($content['filters']['sources'] as $source){
                      print("<li>
                        <input type='checkbox' id='landing-src-".$source['id']."' class='ds-source-checkbox' value='".$source['id']."'>
                        <label for='landing-src-".$source['id']."'>".$source['title']."</label>
                      </li>");
                    }
                    ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="ds-col">
          <a href="/2020/data-search/Search-the-Data" class="ds-input ds-search-button ds-landing-source-search">Search</a>
        </div>
      </div>
    </div>
    <!-- /ds-block -->
    <a href="/2020/data-search/Search-the-Data" class="ds-advanced-search clearfix">Advanced search</a>
  </div>
  <!-- /ds-block-outer -->
</div>