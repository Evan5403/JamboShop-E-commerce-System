<?php
  if ($role !== 'admin' AND $role !== 'marketer') {
    echo "<script>window.open('admin_logout.php','_self');</script>";
  }
 ?>

<div class="header">
    <div class="left">
        <h1>Analytics</h1>
        <ul class="breadcrumb">
            <?php
              if ($role == 'admin') { ?>
              <li><a href="admin_profile.php">
                      Dashboard
                  </a></li>
                  /
                  <li><a href="#" class="active">Analytics</a></li>
            <?php } ?>
        </ul>
    </div>
</div>
<div class="bottom-data">
    <div class="orders">
      <h3>Sales Performance Comparison - By Month</h3>
      <div class="myChart">
        <div class="chart-wrapper">
          <canvas id="salesComparisonChart"></canvas>
        </div>
        <div class="chart-wrapper">
          <canvas id="ordersComparisonChart"></canvas>
        </div>
        <div class="chart-wrapper">
          <canvas id="revenueComparisonChart"></canvas>
        </div>
      </div>
      <h3>Sales Trends And Product Performance</h3>
      <div class="myChart2">
        <div class="chart-wrapper">
          <canvas id="dailySalesChart"></canvas>
        </div>
        <div class="chart-wrapper">
          <canvas id="weeklySalesChart"></canvas>
        </div>
        <div class="chart-wrapper">
          <canvas id="monthlySalesChart"></canvas>
        </div>
        <div class="chart-wrapper">
          <canvas id="productPerformanceChart"></canvas>
        </div>
        <div class="chart-wrapper">
          <canvas id="categoryBreakdown"></canvas>
        </div>
      </div>

    </div>


</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
