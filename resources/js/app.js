import "./bootstrap";

import Alpine from "alpinejs";
import Chart from "chart.js/auto";

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();

// Company Theme Navigation Enhancement
document.addEventListener("DOMContentLoaded", function () {
    // Keyboard shortcuts
    document.addEventListener("keydown", function (e) {
        // Ctrl + K for search
        if (e.ctrlKey && e.key === "k") {
            e.preventDefault();
            const searchInput = document.querySelector('input[type="text"]');
            if (searchInput) {
                searchInput.focus();
            }
        }

        // Ctrl + B for sidebar toggle
        if (e.ctrlKey && e.key === "b") {
            e.preventDefault();
            const toggleButton = document.querySelector(
                '[\\@click="sidebarOpen = !sidebarOpen"]'
            );
            if (toggleButton) {
                toggleButton.click();
            }
        }

        // Esc to close dropdowns
        if (e.key === "Escape") {
            // Close all dropdowns
            const openDropdowns = document.querySelectorAll(
                '[x-data*="open: true"]'
            );
            openDropdowns.forEach((dropdown) => {
                Alpine.store("dropdown", false);
            });
        }
    });

    // Mobile sidebar management
    const handleMobileSidebar = () => {
        const sidebar = document.querySelector(".sidebar-mobile");
        const overlay = document.querySelector(".sidebar-overlay");

        if (window.innerWidth <= 768) {
            sidebar?.classList.add("sidebar-mobile");
        } else {
            sidebar?.classList.remove("sidebar-mobile");
        }
    };

    // Initial check and resize listener
    handleMobileSidebar();
    window.addEventListener("resize", handleMobileSidebar);

    // Add smooth scrolling to navigation links
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            }
        });
    });

    // Add loading states to forms
    document.querySelectorAll("form").forEach((form) => {
        form.addEventListener("submit", function () {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.innerHTML = `
                    <span class="loading-spinner mr-2"></span>
                    Processing...
                `;
                submitButton.disabled = true;
            }
        });
    });

    // Enhanced notifications system
    const showNotification = (message, type = "info") => {
        const notification = document.createElement("div");
        notification.className = `fixed top-4 right-4 z-50 max-w-sm p-4 rounded-xl shadow-xl transform translate-x-full transition-transform duration-300 ${
            type === "success"
                ? "bg-green-500 text-white"
                : type === "error"
                ? "bg-red-500 text-white"
                : type === "warning"
                ? "bg-yellow-500 text-white"
                : "bg-blue-500 text-white"
        }`;

        notification.innerHTML = `
            <div class="flex items-center space-x-3">
                <i class="fas ${
                    type === "success"
                        ? "fa-check-circle"
                        : type === "error"
                        ? "fa-exclamation-circle"
                        : type === "warning"
                        ? "fa-exclamation-triangle"
                        : "fa-info-circle"
                }"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove("translate-x-full");
        }, 100);

        setTimeout(() => {
            notification.classList.add("translate-x-full");
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    };

    // Make notification function globally available
    window.showNotification = showNotification;

    // Auto-hide flash messages
    const flashMessages = document.querySelectorAll(".flash-message");
    flashMessages.forEach((message) => {
        setTimeout(() => {
            message.style.opacity = "0";
            setTimeout(() => message.remove(), 300);
        }, 5000);
    });

    // Add hover effects to cards
    const cards = document.querySelectorAll(".hover-lift");
    cards.forEach((card) => {
        card.addEventListener("mouseenter", function () {
            this.style.transform = "translateY(-4px) scale(1.02)";
        });

        card.addEventListener("mouseleave", function () {
            this.style.transform = "translateY(0) scale(1)";
        });
    });

    // Dark mode toggle functionality
    const darkModeToggle = document.querySelector(".dark-mode-toggle");
    if (darkModeToggle) {
        darkModeToggle.addEventListener("click", function () {
            document.documentElement.classList.toggle("dark");
            localStorage.setItem(
                "darkMode",
                document.documentElement.classList.contains("dark")
            );
        });
    }

    // Initialize dark mode from localStorage
    if (localStorage.getItem("darkMode") === "true") {
        document.documentElement.classList.add("dark");
    }

    // Add ripple effect to buttons
    document.querySelectorAll("button, .btn").forEach((button) => {
        button.addEventListener("click", function (e) {
            const ripple = document.createElement("span");
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + "px";
            ripple.style.left = x + "px";
            ripple.style.top = y + "px";
            ripple.classList.add("ripple");

            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Enhanced search functionality
    const searchInput = document.querySelector('input[placeholder*="Cari"]');
    if (searchInput) {
        let searchTimeout;

        searchInput.addEventListener("input", function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length > 2) {
                searchTimeout = setTimeout(() => {
                    // Implement search logic here
                    console.log("Searching for:", query);
                    showNotification(`Mencari: ${query}`, "info");
                }, 500);
            }
        });
    }
});

// CSS for ripple effect
const style = document.createElement("style");
style.textContent = `
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    button, .btn {
        position: relative;
        overflow: hidden;
    }
`;
document.head.appendChild(style);
