<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12">
      <?php
      foreach($storageEngines as $storage){
        if($storage['storage_usage'] > 90){
          ?>
          <div class="alert alert-danger">
            <p><strong>Please consider upgrading your storage: </strong><?php echo $storage['display_name']; ?> exceeded 90% utilization</p>
          </div>
          <?php
        }elseif ($storage['storage_usage'] > 80) {
          ?>
          <div class="alert alert-warning">
            <p><strong>Please consider upgrading your storage: </strong><?php echo $storage['display_name']; ?> exceeded 80% utilization</p>
          </div>
          <?php
        }
      }
       ?>
    </div>
    <div class="col-md-12">
      <?php
      foreach($warningMessages as $warning){
        ?>
        <p class="alert alert-warning">
          <strong>Configuration Warning: </strong> <?php echo $warning; ?>
        </p>
        <?php
      }
      ?>
    </div>
  </div>
  <div class="row top_tiles">
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <div class="tile-stats">
                    <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
                    <div class="count"><?php echo $files['uploaded_today']; ?></div>
                    <h3>Today</h3>
                    <p>File uploads today.</p>
                  </div>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <div class="tile-stats">
                    <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
                    <div class="count"><?php echo $files['uploaded_yesterday']; ?></div>
                    <h3>Yesterday</h3>
                    <p>File uploads yesterday.</p>
                  </div>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <div class="tile-stats">
                    <div class="icon"><i class="fa fa-globe"></i></div>
                    <div class="count"><?php echo $files['uploaded_total']; ?></div>
                    <h3>Total</h3>
                    <p>Total file uploads.</p>
                  </div>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <div class="tile-stats">
                    <div class="icon"><i class="fa fa-check-square-o"></i></div>
                    <div class="count"><?php echo $files['size']; ?></div>
                    <h3>Total File Size</h3>
                    <p>The total file size of all files.</p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Upload Statistics <small>Last 14 days</small></h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="demo-container" style="height:280px">
                          <div id="placeholder33x" class="demo-placeholder"></div>
                        </div>
                      </div>
                    </div>
                  </div>
           </div>
           <div class="col-md-12 col-sm-12 col-xs-12">
             <div class="x_panel">
               <div class="x_title">
                 <h2>Filesize Statistics <small>Last 14 days</small></h2>
                 <div class="clearfix"></div>
               </div>
               <div class="x_content">
                 <div class="col-md-12 col-sm-12 col-xs-12">
                   <div class="demo-container" style="height:280px">
                     <div id="uploadSizeChart" class="demo-placeholder"></div>
                   </div>
                 </div>
               </div>
             </div>
      </div>
</div>
</div></div>
<!-- /page content -->
<script type="text/javascript">
$('.right_col').css("min-height", $(window).height());
  $(document).ready(function() {
    //define chart clolors ( you maybe add more colors if you want or flot will add it automatic )
    var chartColours = ['#96CA59', '#3F97EB', '#72c380', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282'];

    var d1 = [];
    <?php
    foreach($files['uploadStats'] as $day){
      ?>
      d1.push([<?php echo $day['created']*1000; ?>, <?php echo $day['uploads']; ?>]);
      <?php
    }
     ?>

     var d2 = [];
     <?php
     foreach($files['uploadSizeStats'] as $day){
       ?>
       d2.push([<?php echo $day['created']*1000; ?>, <?php echo ($day['filesize'] / 1024 / 1024 /1024); ?>]);
       <?php
     }
      ?>

     var tickSize = [1, "day"];
     var tformat = "%d/%m/%y";

     //graph options
     var options = {
       grid: {
         show: true,
         aboveData: true,
         color: "#3f3f3f",
         labelMargin: 10,
         axisMargin: 0,
         borderWidth: 0,
         borderColor: null,
         minBorderMargin: 5,
         clickable: true,
         hoverable: true,
         autoHighlight: true,
         mouseActiveRadius: 100
       },
       series: {
         lines: {
           show: true,
           fill: true,
           lineWidth: 2,
           steps: false
         },
         points: {
           show: true,
           radius: 4.5,
           symbol: "circle",
           lineWidth: 3.0
         }
       },
       legend: {
         position: "ne",
         margin: [0, -25],
         noColumns: 0,
         labelBoxBorderColor: null,
         labelFormatter: function(label, series) {
           // just add some space to labes
           return label + '&nbsp;&nbsp;';
         },
         width: 40,
         height: 1
       },
       colors: chartColours,
       shadowSize: 0,
       tooltip: true, //activate tooltip
       tooltipOpts: {
         content: "%s: %y.0",
         xDateFormat: "%d/%m",
         shifts: {
           x: -30,
           y: -50
         },
         defaultTheme: false
       },
       yaxis: {
         min: 0
       },
       xaxis: {
         mode: "time",
         minTickSize: tickSize,
         timeformat: tformat
       }
     };

     var plot = $.plot($("#placeholder33x"), [{
       label: "Files Uploaded",
       data: d1,
       datasetFill: false,
       lines: {
         fillColor: "rgba(150, 202, 89, 0.12)"
       }, //#96CA59 rgba(150, 202, 89, 0.42)
       points: {
         fillColor: "#fff"
       }
     }], options);

     var plot1 = $.plot($("#uploadSizeChart"), [{
       label: "Filesize per day (in GB)",
       data: d2,
       lines: {
         fillColor: "rgba(150, 202, 89, 0.12)"
       }, //#96CA59 rgba(150, 202, 89, 0.42)
       points: {
         fillColor: "#fff"
       }
     }], options);
  });
</script>
