import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

const notificationRoutes = window.libraryRoutes ?? {};

document.addEventListener("alpine:init", () => {
    Alpine.data("notifications", () => ({
        open: false,
        notifications: [],
        unreadCount: 0,

        init() {
            this.loadNotifications();
            setInterval(() => this.loadNotifications(), 30000);
        },

        async loadNotifications() {
            try {
                const response = await fetch(
                    notificationRoutes.notificationsIndex ?? "/notifications",
                );
                const data = await response.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch (error) {
                console.error("Failed to load notifications:", error);
            }
        },

        toggleDropdown() {
            this.open = !this.open;
        },

        async markAsRead(notificationId) {
            try {
                await fetch(
                    notificationRoutes.notificationsMarkRead ??
                        "/notifications/mark-read",
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                        body: JSON.stringify({ id: notificationId }),
                    },
                );

                const notification = this.notifications.find(
                    (item) => item.id === notificationId,
                );
                if (notification && !notification.read) {
                    notification.read = true;
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            } catch (error) {
                console.error("Failed to mark notification as read:", error);
            }
        },

        async handleNotificationClick(notification) {
            if (!notification.read) {
                await this.markAsRead(notification.id);
            }

            if (notification.action_url) {
                window.location.href = notification.action_url;
            }
        },

        async markAllAsRead() {
            try {
                await fetch(
                    notificationRoutes.notificationsMarkAllRead ??
                        "/notifications/mark-all-read",
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                    },
                );

                this.notifications.forEach((notification) => {
                    notification.read = true;
                });
                this.unreadCount = 0;
            } catch (error) {
                console.error(
                    "Failed to mark all notifications as read:",
                    error,
                );
            }
        },

        async clearAllNotifications() {
            if (
                !confirm(
                    "Are you sure you want to clear all notifications? This action cannot be undone.",
                )
            ) {
                return;
            }

            try {
                await fetch(
                    notificationRoutes.notificationsClearAll ??
                        "/notifications/clear-all",
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                    },
                );

                this.notifications = [];
                this.unreadCount = 0;
                this.open = false;
                this.showToast(
                    "All notifications cleared successfully",
                    "success",
                );
            } catch (error) {
                console.error("Failed to clear all notifications:", error);
                this.showToast("Failed to clear notifications", "error");
            }
        },

        showToast(message, type = "info") {
            const toast = document.createElement("div");
            toast.className =
                "fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full";

            const bgColor =
                type === "success"
                    ? "bg-green-600"
                    : type === "error"
                      ? "bg-red-600"
                      : "bg-blue-600";
            toast.className += ` ${bgColor} text-white`;

            const icon =
                type === "success"
                    ? "fas fa-check-circle"
                    : type === "error"
                      ? "fas fa-exclamation-circle"
                      : "fas fa-info-circle";

            toast.innerHTML = `
			<div class="flex items-center gap-2">
			  <i class="${icon}"></i>
			  <span>${message}</span>
			</div>
		  `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove("translate-x-full");
            }, 100);

            setTimeout(() => {
                toast.classList.add("translate-x-full");
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        },
    }));
});

document.addEventListener("DOMContentLoaded", () => {
    Alpine.start();
});
