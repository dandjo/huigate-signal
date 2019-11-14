<?php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title>HuiGate Signal</title>
    </head>
    <body>
        <div class="container">
            <h1>HuiGate Signal (CellID: <span id="cell_id"></span>)</h1>
            <h4>RSRQ <span style="font-size: 90%;">(Reference Signal Received Quality)</span></h4>
            <h5>-20 dB to -3 dB</h5>
            <div class="progress">
                <div id="rsrq" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0"
                        aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span>0</span> dB
                </div>
            </div>
            <h4>RSRP <span style="font-size: 90%;">(Reference Signal Received Power)</span></h4>
            <h5>-110 dBm to -70 dBm</h5>
            <div class="progress">
                <div id="rsrp" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0"
                        aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span>0</span> dBm
                </div>
            </div>
            <h4>SINR <span style="font-size: 90%;">(Signal to Inference &amp; Noise Ratio)</span></h4>
            <h5>0 dB to 30 dB</h5>
            <div class="progress">
                <div id="sinr" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0"
                        aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span>0</span> dB
                </div>
            </div>
            <h4>RSSI <span style="font-size: 90%;">(Received Signal Strength Indicator)</span></h4>
            <h5>-113 dBm to -51 dBm</h5>
            <div class="progress">
                <div id="rssi" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0"
                        aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span>0</span> dBm
                </div>
            </div>
        </div>
        <script>
            var updateProgressBar = function(elemId, value, min, max) {
                var perc = (1 + (value - max) / (max - min)) * 100;
                $('#' + elemId + ' span').text(value);
                $('#' + elemId).css('width', perc + '%').attr('aria-valuenow', perc).removeClass(function(index, classes) {
                    return classes.split(/\s+/).filter(function(c) {
                        var regex = new RegExp('progress-bar-(danger|warning|info|success)');
                        return regex.test(c);
                    }).join(' ');
                }).addClass(function(index, classes) {
                    if (perc < 25) {
                        return 'progress-bar-danger';
                    }
                    if (perc < 50) {
                        return 'progress-bar-warning';
                    }
                    if (perc < 75) {
                        return 'progress-bar-info';
                    }
                    return 'progress-bar-success';
                });
            };

            var signal = function() {
                $.get('<?php echo CONFIG_HTTP_ROOT; ?>/api/signal.php', function(data) {
                    $('#cell_id').text(data['cell_id']);
                    var ids = ['rsrq', 'rsrp', 'sinr', 'rssi'];
                    var mins = [-20, -110, 0, -113];
                    var maxs = [-3, -70, 30, -51];
                    for (var i = 0; i < ids.length; i++) {
                        updateProgressBar(ids[i], data[ids[i]], mins[i], maxs[i]);
                    }
                    signal();
                });
            };
            signal();
	</script>
    </body>
</html>
