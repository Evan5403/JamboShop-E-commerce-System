// add hovered class to selected list item
// let list = document.querySelectorAll(".navigation li");
//
// function activeLink() {
//   list.forEach((item) => {
//     item.classList.remove("hovered");
//   });
//   this.classList.add("hovered");
// }
//
// list.forEach((item) => item.addEventListener("mouseover", activeLink));

// Menu Toggle
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function () {
  navigation.classList.toggle("active");
  main.classList.toggle("active");
};

let list = document.querySelectorAll(".navigation li a");

function setActiveLink() {
  list.forEach((link) => {
    // Remove the 'hovered' class from all links
    link.parentElement.classList.remove("hovered");
    // Add the 'hovered' class if the href matches the current URL
    if (link.href === window.location.href) {
      link.parentElement.classList.add("hovered");
    }
  });
}

// Call the function to set the active link on page load
setActiveLink();

document.addEventListener('DOMContentLoaded', () => {
  fetch('../../functions/fetch_chart_data.php')
    .then(response => response.json())
    .then(data => {
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

      // Most Purchased Products Chart
      const ctxProducts = document.getElementById('mostPurchasedProducts').getContext('2d');
      new Chart(ctxProducts, {
        type: 'bar',
        data: {
          labels: data.products, // Dynamic product names
          datasets: [{
            label: 'Total Purchased',
            data: data.productCounts, // Dynamic product counts
            backgroundColor: '#3498db',
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
                  // Shorten long labels to 15 characters and append '...' if needed
                  const maxLength = 15;
                  return value.length > maxLength ? value.substr(0, maxLength) + '...' : value;
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
              text: 'Most Purchased Products By the User',
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

      // Spending Trends
      const ctxSpending = document.getElementById('spendingTrends').getContext('2d');
      const chartMonths = data.monthlySpent; // Use the 'monthlySpent' key
      const chartSpendings = data.amountDue.map(amount => parseFloat(amount));
      new Chart(ctxSpending, {
        type: 'line', // Line chart for trends over time
        data: {
          labels: chartMonths, // Dynamic months
          datasets: [{
            label: 'Monthly Spending',
            data: chartSpendings, // Dynamic spending amounts
            backgroundColor: 'rgba(52, 152, 219, 0.2)', // Light blue for fill
            borderColor: '#3498db', // Blue for line
            borderWidth: 2,
            fill: true, // Fill under the line
            tension: 0.4, // Smooth curve
          }]
        },
        options: {
          responsive: true, // Ensure responsiveness
          maintainAspectRatio: false, // Prevent aspect ratio locking
          plugins: {
            legend: {
              display: true, // Show legend
              position: 'top', // Place legend at the top
            },
            title: {
              display: true, // Show chart title
              text: 'Spending Trends Over Time', // Chart title text
              font: {
                size: 18, // Title font size
                weight: 'bold', // Title font weight
              },
            },
          },
          scales: {
            x: {
              title: {
                display: true, // Show x-axis title
                text: 'Months', // X-axis title text
                font: {
                  size: 16, // X-axis title font size
                },
              },
              ticks: {
                autoSkip: true, // Skip labels if too many
                maxRotation: 0, // Prevent rotation
                minRotation: 0, // Prevent rotation
              },
            },
            y: {
              title: {
                display: true, // Show y-axis title
                text: 'Total Spending', // Y-axis title text
                font: {
                  size: 16, // Y-axis title font size
                },
              },
              beginAtZero: true, // Start from 0 on the y-axis
            },
          },
        },
      });

    })
    .catch(error => console.error('Error fetching chart data:', error));
});
