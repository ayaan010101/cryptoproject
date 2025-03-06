/**
 * Main JavaScript file for CryptoTrade Platform
 */

document.addEventListener("DOMContentLoaded", function () {
  // Mobile menu toggle
  const mobileMenuButton = document.getElementById("mobile-menu-button");
  const mobileMenu = document.getElementById("mobile-menu");

  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener("click", function () {
      mobileMenu.classList.toggle("hidden");
    });
  }

  // Chart rendering (if canvas exists)
  const chartCanvas = document.getElementById("trading-chart");
  if (chartCanvas) {
    renderTradingChart(chartCanvas);
  }

  // Form validation
  const forms = document.querySelectorAll("form.validate");
  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      let isValid = true;

      // Check required fields
      const requiredFields = form.querySelectorAll("[required]");
      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          isValid = false;
          showFieldError(field, "This field is required");
        } else {
          clearFieldError(field);
        }
      });

      // Check email fields
      const emailFields = form.querySelectorAll('input[type="email"]');
      emailFields.forEach((field) => {
        if (field.value.trim() && !isValidEmail(field.value)) {
          isValid = false;
          showFieldError(field, "Please enter a valid email address");
        }
      });

      // Check password match
      const password = form.querySelector('input[name="password"]');
      const confirmPassword = form.querySelector(
        'input[name="confirm_password"]',
      );
      if (
        password &&
        confirmPassword &&
        password.value !== confirmPassword.value
      ) {
        isValid = false;
        showFieldError(confirmPassword, "Passwords do not match");
      }

      if (!isValid) {
        e.preventDefault();
      }
    });
  });

  // Copy to clipboard functionality
  const copyButtons = document.querySelectorAll(".copy-to-clipboard");
  copyButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const textToCopy = this.getAttribute("data-clipboard-text");
      if (textToCopy) {
        navigator.clipboard
          .writeText(textToCopy)
          .then(() => {
            // Show success message
            const originalText = this.innerHTML;
            this.innerHTML = "Copied!";
            setTimeout(() => {
              this.innerHTML = originalText;
            }, 2000);
          })
          .catch((err) => {
            console.error("Could not copy text: ", err);
          });
      }
    });
  });

  // Initialize tabs if present
  initTabs();
});

// Helper functions
function showFieldError(field, message) {
  // Clear any existing error
  clearFieldError(field);

  // Add error class to field
  field.classList.add("border-red-500");

  // Create and append error message
  const errorElement = document.createElement("p");
  errorElement.className = "text-red-500 text-xs mt-1 field-error";
  errorElement.textContent = message;
  field.parentNode.appendChild(errorElement);
}

function clearFieldError(field) {
  field.classList.remove("border-red-500");
  const errorElement = field.parentNode.querySelector(".field-error");
  if (errorElement) {
    errorElement.remove();
  }
}

function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function initTabs() {
  const tabContainers = document.querySelectorAll(".tabs-container");

  tabContainers.forEach((container) => {
    const tabs = container.querySelectorAll(".tab-trigger");
    const tabContents = container.querySelectorAll(".tab-content");

    tabs.forEach((tab) => {
      tab.addEventListener("click", function () {
        const target = this.getAttribute("data-tab");

        // Update active tab
        tabs.forEach((t) => t.classList.remove("active-tab"));
        this.classList.add("active-tab");

        // Show target content
        tabContents.forEach((content) => {
          if (content.getAttribute("data-tab") === target) {
            content.classList.remove("hidden");
          } else {
            content.classList.add("hidden");
          }
        });
      });
    });
  });
}

function renderTradingChart(canvas) {
  const ctx = canvas.getContext("2d");
  if (!ctx) return;

  const width = canvas.width;
  const height = canvas.height;

  // Generate random data
  const data = generateChartData(100);

  // Draw chart
  drawChart(ctx, data, width, height);
}

function generateChartData(points) {
  const data = [];
  let value = 92000 + Math.random() * 5000; // Starting around 92-97k (like BTC)

  for (let i = 0; i < points; i++) {
    // Create more realistic movements
    const change = (Math.random() - 0.48) * 200; // Slightly biased upward
    value += change;

    // Ensure value stays positive and within a reasonable range
    value = Math.max(value, 90000);
    value = Math.min(value, 99000);

    data.push(value);
  }

  return data;
}

function drawChart(ctx, data, width, height) {
  const max = Math.max(...data) * 1.05; // Add 5% padding
  const min = Math.min(...data) * 0.95; // Subtract 5% padding
  const range = max - min;

  const xStep = width / (data.length - 1);

  // Clear canvas
  ctx.clearRect(0, 0, width, height);

  // Draw grid lines
  ctx.strokeStyle = "#e5e7eb";
  ctx.lineWidth = 1;

  // Horizontal grid lines
  for (let i = 1; i < 5; i++) {
    const y = (height / 5) * i;
    ctx.beginPath();
    ctx.moveTo(0, y);
    ctx.lineTo(width, y);
    ctx.stroke();
  }

  // Vertical grid lines
  for (let i = 1; i < 6; i++) {
    const x = (width / 6) * i;
    ctx.beginPath();
    ctx.moveTo(x, 0);
    ctx.lineTo(x, height);
    ctx.stroke();
  }

  // Draw the line
  ctx.beginPath();
  ctx.strokeStyle = "#10b981"; // Green color
  ctx.lineWidth = 2;

  // Move to first point
  const initialY = height - ((data[0] - min) / range) * height;
  ctx.moveTo(0, initialY);

  // Draw lines to each point
  for (let i = 1; i < data.length; i++) {
    const x = i * xStep;
    const y = height - ((data[i] - min) / range) * height;
    ctx.lineTo(x, y);
  }
  ctx.stroke();

  // Fill area under the line
  ctx.lineTo(width, height);
  ctx.lineTo(0, height);
  ctx.closePath();
  ctx.fillStyle = "rgba(16, 185, 129, 0.1)"; // Transparent green
  ctx.fill();

  // Add price indicators
  ctx.font = "12px Arial";
  ctx.fillStyle = "#6b7280";

  // Current price
  const current = data[data.length - 1];
  ctx.textAlign = "right";
  ctx.fillText(
    `$${current.toLocaleString(undefined, { maximumFractionDigits: 0 })}`,
    width - 10,
    20,
  );

  // High price
  ctx.textAlign = "left";
  ctx.fillText(
    `High: $${max.toLocaleString(undefined, { maximumFractionDigits: 0 })}`,
    10,
    20,
  );

  // Low price
  ctx.fillText(
    `Low: $${min.toLocaleString(undefined, { maximumFractionDigits: 0 })}`,
    10,
    height - 10,
  );
}
