const sideLinks = document.querySelectorAll('.sidebar .side-menu li a:not(.logout)');

// sideLinks.forEach(item => {
//     const li = item.parentElement;
//     item.addEventListener('click', () => {
//         sideLinks.forEach(i => {
//             i.parentElement.classList.remove('active');
//         })
//         li.classList.add('active');
//     })
// });
// Function to set the active class
function setActiveLink() {
    sideLinks.forEach(item => {
        const li = item.parentElement;
        const linkHref = item.href;
        const currentUrl = window.location.href;

        // Check if the link matches the current URL
        if (linkHref === currentUrl) {
            li.classList.add('active');
        } else {
            li.classList.remove('active');
        }

        // Add click event listener to update active state dynamically
        item.addEventListener('click', () => {
            sideLinks.forEach(i => {
                i.parentElement.classList.remove('active');
            });
            li.classList.add('active');
        });
    });
}

// Call the function to set the initial active link
setActiveLink();

const menuBar = document.querySelector('.content nav .bx.bx-menu');
const sideBar = document.querySelector('.sidebar');

menuBar.addEventListener('click', () => {
    sideBar.classList.toggle('close');
});

// const searchBtn = document.querySelector('.content nav form .form-input button');
// const searchBtnIcon = document.querySelector('.content nav form .form-input button .bx');
// const searchForm = document.querySelector('.content nav form');
//
// searchBtn.addEventListener('click', function (e) {
//     if (window.innerWidth < 576) {
//         e.preventDefault;
//         searchForm.classList.toggle('show');
//         if (searchForm.classList.contains('show')) {
//             searchBtnIcon.classList.replace('bx-search', 'bx-x');
//         } else {
//             searchBtnIcon.classList.replace('bx-x', 'bx-search');
//         }
//     }
// });

window.addEventListener('resize', () => {
    if (window.innerWidth < 768) {
        sideBar.classList.add('close');
    } else {
        sideBar.classList.remove('close');
    }
    // if (window.innerWidth > 576) {
    //     searchBtnIcon.classList.replace('bx-x', 'bx-search');
    //     searchForm.classList.remove('show');
    // }
});

const toggler = document.getElementById('theme-toggle');

toggler.addEventListener('change', function () {
    if (this.checked) {
        document.body.classList.add('dark');
    } else {
        document.body.classList.remove('dark');
    }
});

document.addEventListener('DOMContentLoaded', () => {
  fetch('../../functions/fetch_chart_data.php')
  .then(response => response.json())
  .then(data => {
    const performanceData = data.performance_data; // Extract only performance data
    const months = performanceData.map(item => item.month); // Extract months
    const totalOrders = performanceData.map(item => parseInt(item.total_orders)); // Extract orders
    const totalRevenue = performanceData.map(item => parseFloat(item.total_revenue)); // Extract revenue
    const totalSales = performanceData.map(item => parseInt(item.total_sales)); // Extract sales

    // Calculate percentage changes for comparison
    const salesChange = ((totalSales[1] - totalSales[0]) / totalSales[0]) * 100;
    const ordersChange = ((totalOrders[1] - totalOrders[0]) / totalOrders[0]) * 100;
    const revenueChange = ((totalRevenue[1] - totalRevenue[0]) / totalRevenue[0]) * 100;

    const salesTrends = data.sales_trends; // Extract sales trends
    const productPerformance = data.product_performance; // Extract product performance

    // Prepare data for sales trends
    const dailyLabels = salesTrends.map(item => item.date); // Extract daily dates
    const dailyRevenue = salesTrends.map(item => parseFloat(item.daily_revenue)); // Extract daily revenue

    const weeklyLabels = [...new Set(salesTrends.map(item => `Week ${item.week}`))]; // Extract unique weeks
    const weeklyRevenue = weeklyLabels.map(week => {
      return salesTrends
        .filter(item => `Week ${item.week}` === week)
        .reduce((sum, item) => sum + parseFloat(item.daily_revenue), 0);
    });

    const monthlyLabels = [...new Set(salesTrends.map(item => item.month))]; // Extract unique months
    const monthlyRevenue = monthlyLabels.map(month => {
      return salesTrends
        .filter(item => item.month === month)
        .reduce((sum, item) => sum + parseFloat(item.daily_revenue), 0);
    });

    // Line Chart for Sales Comparison
    new Chart(document.getElementById('salesComparisonChart'), {
      type: 'line',
      data: {
        labels: months,
        datasets: [{
          label: 'Total Sales',
          data: totalSales,
          backgroundColor: 'rgba(54, 162, 235, 0.2)', // Light blue fill
          borderColor: 'rgba(54, 162, 235, 1)', // Blue line
          borderWidth: 2,
          fill: true,
          tension: 0.4,
        }]
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: `Sales Comparison (This Month: ${totalSales[1]} - Last Month: ${totalSales[0]} - ${salesChange.toFixed(2)}%)`,
            font: {
              size: 18,
              weight: 'bold',
            },
          },
        },
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Total Sales',
              font: {
                size: 16,
              },
            },
          },
          x: {
            title: {
              display: true,
              text: 'Months',
              font: {
                size: 16,
              },
            },
          },
        },
      },
    });

    // Bar Chart for Orders Comparison
    new Chart(document.getElementById('ordersComparisonChart'), {
      type: 'bar',
      data: {
        labels: months,
        datasets: [{
          label: 'Total Orders',
          data: totalOrders,
          backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)'],
          borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
          borderWidth: 1,
        }]
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: `Orders Comparison (This Month: ${totalOrders[1]} - Last Month: ${totalOrders[0]} - ${ordersChange.toFixed(2)}%)`,
            font: {
              size: 18,
              weight: 'bold',
            },
          },
        },
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Total Orders',
              font: {
                size: 16,
              },
            },
          },
          x: {
            title: {
              display: true,
              text: 'Months',
              font: {
                size: 16,
              },
            },
          },
        },
      },
    });

    // Line Chart for Revenue Comparison
    new Chart(document.getElementById('revenueComparisonChart'), {
      type: 'line',
      data: {
        labels: months,
        datasets: [{
          label: 'Total Revenue',
          data: totalRevenue,
          backgroundColor: 'rgba(255, 99, 132, 0.2)', // Light red fill
          borderColor: 'rgba(255, 99, 132, 1)', // Red line
          borderWidth: 2,
          fill: true,
          tension: 0.4,
        }]
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: `Revenue Comparison (This Month: Ksh.${totalRevenue[1]} - Last Month: Kshs.${totalRevenue[0]} - ${revenueChange.toFixed(2)}%)`,
            font: {
              size: 18,
              weight: 'bold',
            },
          },
        },
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Total Revenue',
              font: {
                size: 16,
              },
            },
          },
          x: {
            title: {
              display: true,
              text: 'Months',
              font: {
                size: 16,
              },
            },
          },
        },
      },
    });

    // Line chart for daily sales trends
    new Chart(document.getElementById('dailySalesChart'), {
      type: 'line',
      data: {
        labels: dailyLabels,
        datasets: [{
          label: 'Daily Revenue',
          data: dailyRevenue,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 2,
          fill: true,
        }]
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: 'Daily Sales Trends',
          },
        },
        responsive: true,
      },
    });

    // Bar chart for weekly sales trends
    new Chart(document.getElementById('weeklySalesChart'), {
      type: 'bar',
      data: {
        labels: weeklyLabels,
        datasets: [{
          label: 'Weekly Revenue',
          data: weeklyRevenue,
          backgroundColor: 'rgba(153, 102, 255, 0.6)',
          borderColor: 'rgba(153, 102, 255, 1)',
          borderWidth: 1,
        }]
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: 'Weekly Sales Trends',
          },
        },
        responsive: true,
      },
    });

    // Line chart for monthly sales trends
    new Chart(document.getElementById('monthlySalesChart'), {
      type: 'line',
      data: {
        labels: monthlyLabels,
        datasets: [{
          label: 'Monthly Revenue',
          data: monthlyRevenue,
          backgroundColor: 'rgba(255, 159, 64, 0.2)',
          borderColor: 'rgba(255, 159, 64, 1)',
          borderWidth: 2,
          fill: true,
        }]
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: 'Monthly Sales Trends',
          },
        },
        responsive: true,
      },
    });

    // Bar chart for product performance (most sold)
    // const productPerformance = data.product_performance; // Extract product performance
    const productLabels = productPerformance.map(item => item.product_title);
    const productSold = productPerformance.map(item => parseInt(item.total_sold));

    // Bar chart for product performance (all products)
    new Chart(document.getElementById('productPerformanceChart'), {
      type: 'bar',
      data: {
        labels: productLabels,
        datasets: [{
          label: 'Units Sold',
          data: productSold,
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
        }]
      },
      options: {
        responsive: true, // Ensures the chart scales with the container
        maintainAspectRatio: false, // Allows custom height and width
        scales: {
          x: {
            title: {
              display: true,
              text: 'Products',
              font: {
                size: 16,
                weight: 'bold',
              },
            },
            ticks: {
              callback: function(value) {
                const label = productLabels[value];
                return label.length > 15 ? label.slice(0, 15) + '...' : label; // Truncate long labels
              },
              font: {
                size: 12, // Adjust the font size for x-axis labels
              },
              color: '#555', // Optional: Customize the label color
            },
          },
          y: {
            title: {
              display: true,
              text: 'Total Purchases',
              font: {
                size: 16,
                weight: 'bold',
              },
            },
          },
        },
        plugins: {
          legend: {
            display: false,
          },
          title: {
            display: true,
            text: 'All Products: Units Sold',
            font: {
              size: 18,
              weight: 'bold',
            },
            padding: {
              top: 10,
              bottom: 20,
            },
          },
        },
      },
    });

    // Category Breakdown Chart
    const ctxCategory = document.getElementById('categoryBreakdown').getContext('2d');
    new Chart(ctxCategory, {
      type: 'doughnut',
      data: {
        labels: data.categories, // Dynamic category names
        datasets: [{
          data: data.percentages, // Dynamic percentages
          backgroundColor: ['#f39c12', '#8e44ad', '#3498db', '#e74c3c', '#2ecc71'], // Customize colors
        }]
      },
      options: {
        plugins: {
          legend: {
            display: true,
            position: 'bottom',
          },
          title: {
            display: true,
            text: 'Purchase Percentage by Category', // Header text
            font: {
              size: 18, // Font size for the header
              weight: 'bold',
            },
            color: '#333', // Header text color
          },
        },
        tooltips: {
          callbacks: {
            label: (tooltipItem, chartData) => {
              const index = tooltipItem.index;
              return `${chartData.labels[index]}: ${chartData.datasets[0].data[index]}%`;
            }
          }
        }
      },
    });
  })
  .catch(error => console.error('Error fetching performance data:', error));


})
