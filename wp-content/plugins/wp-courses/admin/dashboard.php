<div class="wpc-admin-section wpc-admin-box-left">

    <h1 class="wpc-admin-h1">Dashboard</h1>

    <div class="wpc-admin-box-wrapper">

        <?php 
            global $wpdb;
            $table_name = $wpdb->prefix . 'wpc_tracking';
            $sql = "SELECT DISTINCT user_id FROM $table_name";
            $results = $wpdb->get_results($sql);
            $user_count = $wpdb->num_rows;
        ?>

        <div class="wpc-row">
            <div class="wpc-4 wpc-admin-box">
                <h2 class="wpc-admin-box-header" style="text-align: center;">Active Students</h2>
                <div class="wpc-admin-meta-lg" style="color: #FCD46C;"><?php echo (int) $user_count; ?></div>
            </div>
            <div class="wpc-4 wpc-admin-box">
                <h2 class="wpc-admin-box-header" style="text-align: center;">Courses</h2>
                <div class="wpc-admin-meta-lg" style="color: #E21772;"><?php echo wp_count_posts('course')->publish; ?></div>
            </div>
            <div class="wpc-4 wpc-admin-box">
                <h2 class="wpc-admin-box-header" style="text-align: center;">Lessons</h2>
                <div class="wpc-admin-meta-lg" style="color: #89D6E2;"><?php echo wp_count_posts('lesson')->publish; ?></div>
            </div>
        </div>

        <div class="wpc-admin-box" id="wpc-tracking-chart">
            <h2 class="wpc-admin-box-header">Tracking Data</h2>

            <?php 

                $days = (int) get_option('wpc-tracking-overview-days-chart-select', 30);
                if(isset($_POST['wpc-num-days'])) {
                    $days = (int) $_POST['wpc-num-days'];
                    update_option('wpc-tracking-overview-days-chart-select', (int) $_POST['wpc-num-days']);
                }

                $view = (int) get_option('wpc-tracking-overview-view-chart-select', 0);
                if(isset($_POST['wpc-view'])) {
                    $view = (int) $_POST['wpc-view'];
                    update_option('wpc-tracking-overview-view-chart-select', (int) $_POST['wpc-view']);
                }

            ?>

            <p>
                <form action="#wpc-tracking-chart" method="post" class="wpc-chart-filter">
                    <label for="wpc-num-days">Days: </label>
                    <select name="wpc-num-days" id="wpc-num-days">
                        <option value="30" <?php echo $days === 30 ? 'selected' : ''; ?>>30</option>
                        <option value="60" <?php echo $days === 60 ? 'selected' : ''; ?>>60</option>
                        <option value="90" <?php echo $days === 90 ? 'selected' : ''; ?>>90</option>
                        <option value="120" <?php echo $days === 120 ? 'selected' : ''; ?>>120</option>
                        <option value="365" <?php echo $days === 365 ? 'selected' : ''; ?>>365</option>
                    </select>
                    <label for="wpc-view">Action: </label>
                    <select name="wpc-view" id="wpc-view">
                        <option value="0" <?php echo $view === 0 ? 'selected' : ''; ?>>Viewed</option>
                        <option value="1" <?php echo $view === 1 ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </form>
            </p>

            <canvas id="viewsChart" width="400" height="400" style="max-height: 400px;"></canvas>

            <script>

            jQuery(document).ready(function($){
                $(function() {
                    $('#wpc-num-days, #wpc-view, #wpc-active-users-limit, #wpc-active-users-view, #wpc-active-num-days, #wpc-popular-courses-view, #wpc-popular-courses-limit').change(function() {
                        this.form.submit();
                    });
                });
            });

            var ctx = document.getElementById('viewsChart');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [{
                        data: <?php echo json_encode(wpc_get_viewed_lessons_per_day($days, $view)); ?>,
                        backgroundColor: [
                            '#e21672'
                        ],
                        borderColor: [
                            '#e21672'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    plugins: {
                      legend: {
                        position: 'top',
                        display: false,
                      },
                      title: {
                        display: false,
                        text: 'Viewed Lessons Per Day'
                      }
                    },
                }
            });
            </script>

        </div>

    </div>

    <div class="wpc-admin-box-wrapper wpc-container">
        <div class="wpc-row">
            <div class="wpc-admin-box" id="wpc-popular-courses">
                <h2 class="wpc-admin-box-header">Popular Courses by % Viewed/Completed</h2>

                    <?php
                        $view = (int) get_option('wpc-popular-courses-view-chart-select', 0);
                        if(isset($_POST['wpc-popular-courses-view'])) {
                            $view = (int) $_POST['wpc-popular-courses-view'];
                            update_option('wpc-popular-courses-view-chart-select', (int) $_POST['wpc-popular-courses-view']);
                        } 

                        $limit = (int) get_option('wpc-popular-courses-limit-chart-select', 10);
                        if(isset($_POST['wpc-popular-courses-limit'])){
                            $limit = (int) $_POST['wpc-popular-courses-limit'];
                            update_option('wpc-popular-courses-limit-chart-select', (int) $_POST['wpc-popular-courses-limit']);
                        }
                    ?>

                    <?php $percent_viewed = wpc_get_average_percent( $view, $limit ); ?>

                <p>
                    <form action="#wpc-popular-courses" method="post" class="wpc-chart-filter">
                        <label for="wpc-popular-courses-view">By: </label>
                        <select id="wpc-popular-courses-view" name="wpc-popular-courses-view">
                            <option value="0" <?php echo $view === 0 ? 'selected' : ''; ?>>Views</option>
                            <option value="1" <?php echo $view === 1 ? 'selected' : ''; ?>>Completions</option>
                        </select>
                        <label for="wpc-popular-courses-limit">Limit: </label>
                        <select id="wpc-popular-courses-limit" name="wpc-popular-courses-limit">
                            <option value="10" <?php echo $limit === 10 ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?php echo $limit === 25 ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?php echo $limit === 50 ? 'selected' : ''; ?>>50</option>
                        </select>
                    </form>
                </p>

                <?php if(!empty($percent_viewed)) { ?>

                    <p>
                        <canvas id="viewedChart" width="400" height="400" style="max-height: 400px;"></canvas>
                    </p>

                    <script>
                    var ctx = document.getElementById('viewedChart');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($percent_viewed[0]); ?>,
                            datasets: [{
                                data: <?php echo json_encode($percent_viewed[1]); ?>,
                                backgroundColor: [
                                    '#FCD46C',
                                    '#E21772',
                                    '#89D6E2',
                                    '#59296b',
                                    //'#31CC0E'
                                ],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            // indexAxis: 'y',
                            scales: {
                                x: {
                                    display: false,
                                }
                            },
                            plugins: {
                              legend: {
                                position: 'top',
                                display: true,
                              },
                              title: {
                                display: false,
                                text: 'Most Popular Courses by Percentage Viewed'
                              }
                            },
                        }
                    });
                    </script>

                <?php } else { echo '<p>No data yet.</p>'; } ?>

            </div>
        </div>
    </div>
    <div class="wpc-admin-box-wrapper wpc-container">
        <div class="wpc-row">
            <div class="wpc-admin-box" id="wpc-active-users">
                <h2 class="wpc-admin-box-header">Most Active Users</h2>

                <?php 

                    $view = (int) get_option('wpc-active-users-view-chart-select', 0);
                    if(isset($_POST['wpc-active-users-view'])) {
                        $view = (int) $_POST['wpc-active-users-view'];
                        update_option('wpc-active-users-view-chart-select', (int) $_POST['wpc-active-users-view']);
                    } 

                    $limit = (int) get_option('wpc-active-users-limit-chart-select', 10);
                    if(isset($_POST['wpc-active-users-limit'])){
                        $limit = (int) $_POST['wpc-active-users-limit'];
                        update_option('wpc-active-users-limit-chart-select', (int) $_POST['wpc-active-users-limit']);
                    }

                    $seconds = (int) get_option('wpc-active-users-days-chart-select', 604800);
                    if(isset($_POST['wpc-active-num-days'])) {
                        $seconds = $_POST['wpc-active-num-days'];
                        update_option('wpc-active-users-days-chart-select', (int) $_POST['wpc-active-num-days']);
                    }

                ?>

                <p>
                    <form action="#wpc-active-users" method="post" class="wpc-chart-filter">
                        <label for="wpc-active-users-view">By: </label>
                        <select id="wpc-active-users-view" name="wpc-active-users-view">
                            <option value="0" <?php echo $view === 0 ? 'selected' : ''; ?>>Views</option>
                            <option value="1" <?php echo $view === 1 ? 'selected' : ''; ?>>Completions</option>
                        </select>
                        <label for="wpc-active-users-limit">Limit: </label>
                        <select id="wpc-active-users-limit" name="wpc-active-users-limit">
                            <option value="10" <?php echo $limit === 10 ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?php echo $limit === 25 ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?php echo $limit === 50 ? 'selected' : ''; ?>>50</option>
                        </select>
                        <label for="wpc-active-num-days">Days: </label>
                        <select name="wpc-active-num-days" id="wpc-active-num-days">
                            <option value="604800" <?php echo $seconds == 604800 ? 'selected' : ''; ?>>7</option>
                            <option value="2629743" <?php echo $seconds == 2629743 ? 'selected' : ''; ?>>30</option>
                            <option value="7889229" <?php echo $seconds == 7889229 ? 'selected' : ''; ?>>90</option>
                            <option value="31556926" <?php echo $seconds == 31556926 ? 'selected' : ''; ?>>365</option>
                        </select>
                    </form>
                </p>

                <p>
                    <canvas id="activeUsersChart" width="400" height="400" style="max-height: 400px;"></canvas>
                </p>
                
                <script>
                var ctx = document.getElementById('activeUsersChart');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        datasets: [{
                            data: <?php echo json_encode( wpc_get_active_users( $limit, $view, $seconds ) ); ?>,
                            backgroundColor: [
                                '#FCD46C',
                                '#E21772',
                                '#89D6E2',
                                '#59296b',
                                //'#31CC0E'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        plugins: {
                          legend: {
                            position: 'top',
                            display: false,
                          },
                          title: {
                            display: false,
                            text: 'Most Active Users'
                          }
                        },
                    }
                });
                </script>

            </div>
        </div>
    </div>

    <?php do_action('wpc_after_dashboard_data'); ?>

</div>
<div class="wpc-admin-section wpc-admin-box-right">
    <h1 class="wpc-admin-h1">News</h1>
    <div class="wpc-admin-box-wrapper">
        <div class="wpc-admin-box">
            <h2 class="wpc-admin-box-header">Affiliate Program Launched</h2>
            <p>You can now join the WP Courses affiliate program to receive 50% of any sales of WP Courses Premium made from traffic you've referred within a 30 day time period.  Simply sign up for the program and begin sharing your unique affiliate link to get started.</p>
            <a href="https://wpcoursesplugin.com/affiliate-dashboard/" class="wpc-admin-button wpc-admin-button-lg">Learn More</a>
        </div>
    </div>
    <h1 class="wpc-admin-h1">Other</h1>
    <div class="wpc-admin-box-wrapper">
        <div class="wpc-admin-box">
            <h2 class="wpc-admin-box-header">
                Leave a Review 
                <span style="color: #f9d526;">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </span>
            </h2>
            <p>Do you like WP Courses?  Help out by taking a couple minutes to leave a review.</p>
            <a href="https://wordpress.org/support/plugin/wp-courses/reviews/" class="wpc-admin-button wpc-admin-button-lg">Leave a Review</a>
        </div>
    </div>
</div>