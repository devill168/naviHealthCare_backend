<template>
  <div class="layout" :class="{ 'sidebar-open': isSidebarOpen }">
    <!-- Mobile overlay -->
    <button
      v-if="isSidebarOpen && isMobile"
      class="overlay"
      type="button"
      @click="closeSidebar"
      aria-label="Close sidebar"
    ></button>

    <!-- Sidebar -->
    <aside
      class="sidebar"
      :class="{ closed: !isSidebarOpen && !isMobile }"
      :aria-hidden="isMobile && !isSidebarOpen"
    >
      <div class="sidebar-top">
        <div v-show="isSidebarOpen || isMobile" class="brand-wrap">
          <div class="brand-logo">F</div>
          <div class="brand-meta">
            <div class="brand-title">Find ME</div>
<!--            <div class="brand-subtitle">Admin Dashboard</div>-->
          </div>
        </div>

        <button
          class="icon-btn sidebar-toggle-btn"
          type="button"
          @click="toggleSidebar"
          aria-label="Toggle sidebar"
        >
          <i class="fas fa-bars"></i>
        </button>
      </div>

      <nav class="menu">
        <!-- Home -->
        <RouterLink
          v-if="canSeeAllMenus"
          to="/home"
          class="menu-link"
          @click="closeOnMobile"
        >
          <span class="menu-icon">
            <i class="fas fa-house"></i>
          </span>
          <span v-show="isSidebarOpen || isMobile" class="menu-text">
            {{ t("menu.home") }}
          </span>
        </RouterLink>

        <!-- Find Location -->
        <RouterLink
          to="/health-facilities/find-location"
          class="menu-link"
          @click="closeOnMobile"
        >
          <span class="menu-icon">
            <i class="fas fa-location-crosshairs"></i>
          </span>
          <span v-show="isSidebarOpen || isMobile" class="menu-text">
            {{ t("healthFacility.findLocationTitle") }}
          </span>
        </RouterLink>

        <!-- Address -->
        <div v-if="canSeeAllMenus" class="menu-group">
          <button
            class="menu-link menu-group-btn"
            type="button"
            @click="toggleGroup('location')"
            :aria-expanded="openGroups.location"
          >
            <span class="menu-icon">
              <i class="fas fa-map-location-dot"></i>
            </span>

            <span v-show="isSidebarOpen || isMobile" class="menu-text">
              {{ t("menu.address") }}
            </span>

            <span
              v-show="isSidebarOpen || isMobile"
              class="menu-arrow"
              :class="{ open: openGroups.location }"
            >
              <i class="fas fa-angle-down"></i>
            </span>
          </button>

          <div
            v-show="(isSidebarOpen || isMobile) && openGroups.location"
            class="submenu"
          >
            <RouterLink to="/provinces" class="submenu-link" @click="closeOnMobile">
              <span class="submenu-dot"></span>
              <span>{{ t("menu.provinces") }}</span>
            </RouterLink>

            <RouterLink to="/districts" class="submenu-link" @click="closeOnMobile">
              <span class="submenu-dot"></span>
              <span>{{ t("menu.districts") }}</span>
            </RouterLink>

            <RouterLink to="/communes" class="submenu-link" @click="closeOnMobile">
              <span class="submenu-dot"></span>
              <span>{{ t("menu.commune") }}</span>
            </RouterLink>

          <RouterLink to="/villages" class="submenu-link" @click="closeOnMobile">
              <span class="submenu-dot"></span>
              <span>{{ t("menu.villages") }}</span>
          </RouterLink>


          </div>
        </div>

          <!-- Find Location -->
          <RouterLink v-if="canSeeAllMenus"
              to="/health-facilities"
              class="menu-link"
              @click="closeOnMobile"
          >
          <span class="menu-icon">
            <i class="fas fa-hospital"></i>
          </span>
              <span v-show="isSidebarOpen || isMobile" class="menu-text">
            {{ t("menu.health_facility") }}
          </span>
          </RouterLink>

        <!-- Management -->
        <div v-if="canSeeAllMenus" class="menu-group">
          <button
            class="menu-link menu-group-btn"
            type="button"
            @click="toggleGroup('management')"
            :aria-expanded="openGroups.management"
          >
            <span class="menu-icon">
              <i class="fas fa-users-gear"></i>
            </span>

            <span v-show="isSidebarOpen || isMobile" class="menu-text">
              {{ t("menu.management") }}
            </span>

            <span
              v-show="isSidebarOpen || isMobile"
              class="menu-arrow"
              :class="{ open: openGroups.management }"
            >
              <i class="fas fa-angle-down"></i>
            </span>
          </button>

          <div
            v-show="(isSidebarOpen || isMobile) && openGroups.management"
            class="submenu"
          >
            <RouterLink to="/register" class="submenu-link" @click="closeOnMobile">
              <span class="submenu-dot"></span>
              <span>{{ t("menu.users") }}</span>
            </RouterLink>

            <RouterLink to="/roles" class="submenu-link" @click="closeOnMobile">
              <span class="submenu-dot"></span>
              <span>{{ t("menu.roles") }}</span>
            </RouterLink>
          </div>
        </div>
      </nav>
    </aside>

    <!-- Content -->
    <div class="content">
      <header class="topbar">
        <div class="topbar-left">
          <button
            class="icon-btn icon-navbar"
            type="button"
            @click="toggleSidebar"
            aria-label="Toggle sidebar"
          >
            <i class="fas fa-bars"></i>
          </button>

          <div class="topbar-brand">
            <img src="@/assets/logo_moh.png" alt="Logo MOH" class="logo" />
            <div class="topbar-brand-text">
              <div class="title">{{ t("menu.title") }}</div>
              <div class="topbar-caption">Ministry of Health</div>
            </div>
          </div>
        </div>

        <div class="topbar-right">
          <!-- Language -->
          <div class="topbar-control lang-control">
            <button
              class="lang-pill"
              type="button"
              :class="{ active: locale === 'km' }"
              @click="setLocale('km')"
              title="Khmer"
            >
              <img class="flag" :src="flagKh" alt="Cambodia flag" />
              <span class="lang-label">KH</span>
            </button>

            <button
              class="lang-pill"
              type="button"
              :class="{ active: locale === 'en' }"
              @click="setLocale('en')"
              title="English"
            >
              <img class="flag" :src="flagEn" alt="English flag" />
              <span class="lang-label">EN</span>
            </button>
          </div>

          <!-- Theme -->
          <div class="topbar-control theme-control">
            <span class="theme-icon">
              <i :class="isDark ? 'fas fa-moon' : 'fas fa-sun'"></i>
            </span>

            <button
              class="switch"
              type="button"
              :class="{ on: isDark }"
              :aria-pressed="isDark"
              aria-label="Toggle theme"
              @click="toggleDark"
            >
              <span class="knob"></span>
            </button>
          </div>

          <!-- Notifications -->
          <button class="icon-btn notify-btn" type="button" aria-label="Notifications">
            <i class="fas fa-bell"></i>
          </button>

          <!-- User -->
          <div class="user-dropdown" ref="userDropdownRef">
            <button
              type="button"
              class="user-box user-box-btn"
              @click="toggleUserMenu"
              aria-label="Open user menu"
            >
              <div class="user-info">
                <div class="username">{{ displayUsername }}</div>
                <div class="role">{{ displayRole }}</div>
              </div>

              <div class="avatar-ring">
                <img :src="avatarUrl" class="avatar" alt="User avatar" />
              </div>

              <span class="user-caret">
                <i class="fas fa-angle-down"></i>
              </span>
            </button>

            <transition name="dropdown-fade">
              <div v-if="isUserMenuOpen" class="user-menu">
                <div class="user-menu-header">
                  <div class="user-menu-name">{{ displayUsername }}</div>
                  <div class="user-menu-role">{{ displayRole }}</div>
                </div>

                <button type="button" class="user-menu-item danger" @click="logout">
                  <i class="fas fa-right-from-bracket"></i>
                  <span>{{ t("menu.logout") || "Logout" }}</span>
                </button>
              </div>
            </transition>
          </div>
        </div>
      </header>

      <main class="main">
        <RouterView />
      </main>

      <footer class="content-footer">
        <div class="content-footer-left">
          © 2026 Find ME
        </div>

        <div class="content-footer-right">
<!--          <span>Modern Admin Layout</span>-->
<!--          <span class="footer-dot"></span>-->
          <span>Version 1.0</span>
        </div>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from "vue";
import { RouterLink, RouterView, useRouter } from "vue-router";
import { useI18n } from "vue-i18n";

import flagKh from "@/assets/flag_cambodia.png";
import flagEn from "@/assets/flag_english.png";
import defaultAvatar from "@/assets/user.png";

const MOBILE_BREAKPOINT = 900;

const router = useRouter();
const { locale: i18nLocale, t } = useI18n();

const DEFAULT_USER = Object.freeze({
  id: null,
  username: "Guest",
  name: "Guest",
  email: "guest@example.com",
  profile_image_url: defaultAvatar,
  photo: defaultAvatar,
  role: {
    name: "Guest",
  },
  role_name: "Guest",
  roles: [],
});

const isSidebarOpen = ref(true);
const isMobile = ref(false);
const isDark = ref(false);
const currentUser = ref({ ...DEFAULT_USER });
const isUserMenuOpen = ref(false);
const userDropdownRef = ref(null);

const openGroups = ref({
  management: false,
  location: false,
});

const locale = computed(() => i18nLocale.value);

const avatarUrl = computed(() => {
  return (
    currentUser.value?.profile_image_url || defaultAvatar
  );
});

const roleName = computed(() => {
  return (
    currentUser.value?.role?.name ||
    currentUser.value?.role_name ||
    currentUser.value?.roles?.[0]?.name ||
    "Guest"
  );
});

const displayUsername = computed(() => {
  return (
    currentUser.value?.username ||
    currentUser.value?.name ||
    currentUser.value?.email ||
    "Guest"
  );
});

const displayRole = computed(() => {
  return roleName.value || "Guest";
});

const normalizedRole = computed(() => {
  return String(roleName.value || "Guest").trim().toLowerCase();
});

const isAdmin = computed(() => normalizedRole.value === "admin");
const canSeeAllMenus = computed(() => isAdmin.value);

function normalizeUser(user) {
  if (!user || typeof user !== "object") {
    return { ...DEFAULT_USER };
  }

  const actualUser = user?.user && typeof user.user === "object" ? user.user : user;

  const resolvedRoleName =
    actualUser?.role?.name ||
    actualUser?.role_name ||
    actualUser?.roles?.[0]?.name ||
    "Guest";

  return {
    ...DEFAULT_USER,
    ...actualUser,
    id: actualUser?.id ?? null,
    username: actualUser?.username || actualUser?.name || actualUser?.email || "Guest",
    name: actualUser?.name || actualUser?.username || "Guest",
    email: actualUser?.email || "guest@example.com",
    profile_image_url:
      actualUser?.profile_image_url ||
      actualUser?.photo_url ||
      actualUser?.avatar ||
      actualUser?.photo ||
      defaultAvatar,
    photo:
      actualUser?.photo ||
      actualUser?.profile_image_url ||
      actualUser?.photo_url ||
      actualUser?.avatar ||
      defaultAvatar,
    role: {
      name: resolvedRoleName,
    },
    role_name: resolvedRoleName,
    roles: Array.isArray(actualUser?.roles) ? actualUser.roles : [],
  };
}

function safeParseUser() {
  try {
    const raw = localStorage.getItem("user");

    if (!raw || raw === "null" || raw === "undefined") {
      return { ...DEFAULT_USER };
    }

    const parsedUser = JSON.parse(raw);
    return normalizeUser(parsedUser);
  } catch (error) {
    console.error("Invalid user data in localStorage:", error);
    localStorage.removeItem("user");
    return { ...DEFAULT_USER };
  }
}

function setDefaultUser() {
  currentUser.value = { ...DEFAULT_USER };
}

function loadCurrentUser() {
  const parsed = safeParseUser();

  if (!parsed || !parsed.username) {
    setDefaultUser();
    return;
  }

  currentUser.value = normalizeUser(parsed);
}

function updateViewportState() {
  isMobile.value = window.innerWidth <= MOBILE_BREAKPOINT;
  isSidebarOpen.value = !isMobile.value;
}

function applyTheme(dark) {
  document.documentElement.classList.toggle("dark", dark);
}

function setLocale(lang) {
  i18nLocale.value = lang;
  localStorage.setItem("app_locale", lang);
}

function toggleDark() {
  isDark.value = !isDark.value;
  localStorage.setItem("app_theme", isDark.value ? "dark" : "light");
  applyTheme(isDark.value);
}

function toggleGroup(groupKey) {
  openGroups.value = {
    management: false,
    location: false,
    [groupKey]: !openGroups.value[groupKey],
  };
}

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value;
}

function closeSidebar() {
  isSidebarOpen.value = false;
}

function closeOnMobile() {
  if (isMobile.value) {
    closeSidebar();
  }
}

function toggleUserMenu() {
  isUserMenuOpen.value = !isUserMenuOpen.value;
}

function closeUserMenu() {
  isUserMenuOpen.value = false;
}

function handleClickOutside(event) {
  if (!userDropdownRef.value) return;

  if (!userDropdownRef.value.contains(event.target)) {
    closeUserMenu();
  }
}

function handleStorageChange(event) {
  if (event.key === "user") {
    loadCurrentUser();
  }

  if (event.key === "app_locale") {
    const savedLocale = localStorage.getItem("app_locale");
    if (savedLocale === "km" || savedLocale === "en") {
      i18nLocale.value = savedLocale;
    }
  }

  if (event.key === "app_theme") {
    const savedTheme = localStorage.getItem("app_theme");
    isDark.value = savedTheme === "dark";
    applyTheme(isDark.value);
  }
}

async function logout() {
  closeUserMenu();
  localStorage.removeItem("user");
  localStorage.removeItem("token");
  setDefaultUser();
  await router.replace("/");
}

onMounted(() => {
  updateViewportState();
  window.addEventListener("resize", updateViewportState);
  window.addEventListener("storage", handleStorageChange);
  document.addEventListener("click", handleClickOutside);

  const savedTheme = localStorage.getItem("app_theme");
  isDark.value = savedTheme === "dark";
  applyTheme(isDark.value);

  const savedLocale = localStorage.getItem("app_locale");
  if (savedLocale === "km" || savedLocale === "en") {
    i18nLocale.value = savedLocale;
  }

  loadCurrentUser();
});

onBeforeUnmount(() => {
  window.removeEventListener("resize", updateViewportState);
  window.removeEventListener("storage", handleStorageChange);
  document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
:global(html) {
  --bg: #edf1f7;
  --panel: #ffffff;
  --panel-soft: #f8fafc;
  --panel-strong: #ffffff;
  --sidebar: #22384b;
  --sidebar-2: #1d3142;
  --sidebar-border: rgba(255, 255, 255, 0.06);
  --border: #d9e1ea;
  --text: #23384a;
  --text-soft: #5b7083;
  --muted: #8aa0b5;
  --hover: rgba(255, 255, 255, 0.06);
  --active: rgba(255, 255, 255, 0.09);
  --content-hover: #f3f6fa;
  --primary: #2d8cff;
  --primary-soft: rgba(45, 140, 255, 0.12);
  --danger: #d63b3b;
  --warning: #f59e0b;
  --shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
}

:global(html.dark) {
  --bg: #0f172a;
  --panel: #111827;
  --panel-soft: #0f172a;
  --panel-strong: #111827;
  --sidebar: #0f1d2b;
  --sidebar-2: #0b1723;
  --sidebar-border: rgba(255, 255, 255, 0.05);
  --border: rgba(255, 255, 255, 0.08);
  --text: #e5edf5;
  --text-soft: #b8c7d6;
  --muted: #90a4b8;
  --hover: rgba(255, 255, 255, 0.07);
  --active: rgba(255, 255, 255, 0.11);
  --content-hover: rgba(255, 255, 255, 0.05);
  --primary: #60a5fa;
  --warning: #fbbf24;
  --shadow: 0 12px 30px rgba(0, 0, 0, 0.24);
}

:global(body) {
  margin: 0;
  padding: 0;
  background: var(--bg);
  color: var(--text);
  font-family: Inter, "Khmer OS Battambang", Arial, sans-serif;
}

:global(*) {
  box-sizing: border-box;
}

.layout {
  min-height: 100vh;
  display: flex;
  background: var(--bg);
  position: relative;
}

.overlay {
  position: fixed;
  inset: 0;
  z-index: 99;
  border: 0;
  background: rgba(0, 0, 0, 0.35);
}

.sidebar {
  width: 260px;
  min-height: 100vh;
  background: linear-gradient(180deg, var(--sidebar) 0%, var(--sidebar-2) 100%);
  color: #d9e6f1;
  display: flex;
  flex-direction: column;
  transition: width 0.25s ease, transform 0.25s ease;
  border-right: 1px solid var(--sidebar-border);
  position: sticky;
  top: 0;
}

.sidebar.closed {
  width: 74px;
}

.sidebar-top {
  min-height: 64px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  padding: 0 14px;
  border-bottom: 1px solid var(--sidebar-border);
}

.brand-wrap {
  display: flex;
  align-items: center;
  gap: 12px;
  min-width: 0;
}

.brand-logo {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  background: var(--primary);
  color: #fff;
  display: grid;
  place-items: center;
  font-size: 18px;
  font-weight: 800;
  flex-shrink: 0;
}

.brand-meta {
  min-width: 0;
}

.brand-title {
  color: #fff;
  font-size: 18px;
  font-weight: 700;
  line-height: 1.1;
}

.brand-subtitle {
  color: #9db2c6;
  font-size: 12px;
  margin-top: 2px;
}

.menu {
  flex: 1;
  overflow-y: auto;
  padding: 12px 10px;
}

.menu-group {
  margin-bottom: 4px;
}

.menu-link {
  width: 100%;
  min-height: 46px;
  padding: 0 12px;
  border-radius: 12px;
  text-decoration: none;
  color: #dce7f1;
  display: flex;
  align-items: center;
  gap: 12px;
  border: 0;
  background: transparent;
  cursor: pointer;
  text-align: left;
  transition: background 0.2s ease, color 0.2s ease;
}

.menu-link:hover {
  background: var(--hover);
  color: #ffffff;
}

.menu-link.router-link-exact-active {
  background: var(--active);
  color: #ffffff;
}

.menu-group-btn {
  appearance: none;
}

.menu-icon {
  width: 22px;
  min-width: 22px;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  font-size: 15px;
}

.menu-text {
  flex: 1;
  font-size: 13px;
  font-weight: 500;
  white-space: nowrap;
}

.menu-arrow {
  margin-left: auto;
  color: #9fb2c6;
  transition: transform 0.2s ease;
}

.menu-arrow.open {
  transform: rotate(180deg);
}

.submenu {
  margin: 6px 0 10px 42px;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.submenu-link {
  min-height: 36px;
  padding: 8px 12px;
  border-radius: 10px;
  text-decoration: none;
  color: #c5d4e2;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13px;
}

.submenu-link:hover {
  background: rgba(255, 255, 255, 0.05);
  color: #ffffff;
}

.submenu-link.router-link-exact-active {
  background: rgba(255, 255, 255, 0.08);
  color: #ffffff;
}

.submenu-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #8ea4b7;
  flex-shrink: 0;
}

.sidebar.closed .menu-link {
  justify-content: center;
  padding-left: 0;
  padding-right: 0;
}

.sidebar.closed .submenu,
.sidebar.closed .brand-wrap,
.sidebar.closed .sidebar-footer {
  display: none !important;
}

.sidebar-bottom {
  padding: 12px 10px 16px;
  border-top: 1px solid var(--sidebar-border);
}

.sidebar-footer {
  margin-top: 12px;
  text-align: center;
  color: #8fa5b9;
}

.sidebar-footer-line {
  width: 100%;
  height: 1px;
  background: var(--sidebar-border);
  margin-bottom: 10px;
}

.sidebar-footer-brand {
  color: #ffffff;
  font-weight: 700;
  margin-bottom: 4px;
}

.sidebar-footer small {
  font-size: 11px;
}

.content {
  flex: 1;
  min-width: 0;
  display: flex;
  flex-direction: column;
}

.topbar {
  height: 70px;
  padding: 0 18px;
  background: var(--panel);
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  box-shadow: 0 1px 0 rgba(15, 23, 42, 0.02);
}

.topbar-left,
.topbar-right {
  display: flex;
  align-items: center;
  gap: 14px;
}

.topbar-brand {
  display: flex;
  align-items: center;
  gap: 12px;
  min-width: 0;
}

.logo {
  width: 62px;
  height: 62px;
  object-fit: contain;
  border-radius: 12px;
  padding: 5px;
}

.topbar-brand-text {
  min-width: 0;
}

.topbar-caption {
  color: var(--muted);
  font-size: 12px;
  line-height: 1.1;
}

.title {
  color: var(--text);
  font-size: 16px;
  font-weight: 700;
  line-height: 1.2;
}

.topbar-control {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  min-height: 42px;
  padding: 0 10px;
  border-radius: 12px;
  background: var(--panel-soft);
  border: 1px solid var(--border);
}

.lang-control {
  gap: 8px;
}

.lang-pill {
  min-width: 58px;
  height: 32px;
  padding: 0 10px;
  border-radius: 10px;
  border: 1px solid transparent;
  background: transparent;
  color: var(--text-soft);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  cursor: pointer;
  font-weight: 600;
}

.lang-pill:hover {
  background: var(--content-hover);
}

.lang-pill.active {
  background: var(--primary-soft);
  border-color: rgba(45, 140, 255, 0.15);
  color: var(--primary);
}

.flag {
  width: 20px;
  height: 14px;
  object-fit: cover;
  border-radius: 3px;
}

.lang-label {
  font-size: 12px;
}

.theme-control {
  gap: 10px;
}

.theme-icon {
  width: 20px;
  display: inline-flex;
  justify-content: center;
  color: var(--warning);
  font-size: 14px;
}

.switch {
  width: 46px;
  height: 24px;
  border-radius: 999px;
  border: 0;
  background: #d4dce5;
  position: relative;
  cursor: pointer;
  transition: background 0.2s ease;
}

.switch.on {
  background: var(--primary);
}

.knob {
  position: absolute;
  top: 3px;
  left: 3px;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: #ffffff;
  transition: left 0.2s ease;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.2);
}

.switch.on .knob {
  left: 25px;
}

.icon-btn {
  width: 42px;
  height: 42px;
  border: 1px solid var(--border);
  background: var(--panel-soft);
  color: var(--text);
  border-radius: 12px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.icon-btn:hover {
  background: var(--content-hover);
}

.notify-btn {
  position: relative;
}

.notify-btn::after {
  content: "";
  position: absolute;
  top: 10px;
  right: 10px;
  width: 8px;
  height: 8px;
  background: #ef4444;
  border-radius: 50%;
}

.user-dropdown {
  position: relative;
}

.user-box {
  display: flex;
  align-items: center;
  gap: 12px;
  padding-left: 4px;
}

.user-box-btn {
  border: 0;
  background: transparent;
  cursor: pointer;
  padding: 0;
  color: inherit;
}

.user-info {
  text-align: right;
}

.username {
  font-size: 13px;
  font-weight: 700;
  color: var(--text);
}

.role {
  font-size: 12px;
  color: var(--muted);
}

.avatar-ring {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  padding: 2px;
  background: linear-gradient(135deg, var(--primary), #8b5cf6);
}

.avatar {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  display: block;
  object-fit: cover;
  background: var(--panel);
}

.user-caret {
  color: var(--muted);
  font-size: 12px;
  display: inline-flex;
  align-items: center;
}

.user-menu {
  position: absolute;
  top: calc(100% + 10px);
  right: 0;
  min-width: 220px;
  background: var(--panel);
  border: 1px solid var(--border);
  border-radius: 14px;
  box-shadow: var(--shadow);
  overflow: hidden;
  z-index: 120;
}

.user-menu-header {
  padding: 14px 16px 12px;
  border-bottom: 1px solid var(--border);
  background: var(--panel-soft);
}

.user-menu-name {
  font-size: 14px;
  font-weight: 700;
  color: var(--text);
}

.user-menu-role {
  margin-top: 4px;
  font-size: 12px;
  color: var(--muted);
}

.user-menu-item {
  width: 100%;
  min-height: 44px;
  padding: 0 16px;
  border: 0;
  background: transparent;
  color: var(--text);
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  text-align: left;
  font-size: 13px;
  font-weight: 600;
}

.user-menu-item:hover {
  background: var(--content-hover);
}

.user-menu-item.danger {
  color: #ef4444;
}

.icon-navbar {
  display: none;
}

.main {
  flex: 1;
  padding: 18px;
  background: var(--bg);
}

.content-footer {
  height: 52px;
  padding: 0 18px;
  background: var(--panel);
  border-top: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  color: var(--muted);
  font-size: 12px;
}

.content-footer-right {
  display: flex;
  align-items: center;
  gap: 10px;
}

.footer-dot {
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background: var(--muted);
}

.dropdown-fade-enter-active,
.dropdown-fade-leave-active {
  transition: all 0.18s ease;
}

.dropdown-fade-enter-from,
.dropdown-fade-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}

@media (max-width: 900px) {
  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 100;
    height: 100vh;
    transform: translateX(-100%);
    width: 260px;
    box-shadow: var(--shadow);
  }

  .layout.sidebar-open .sidebar {
    transform: translateX(0);
  }

  .sidebar.closed {
    width: 260px;
  }

  .sidebar.closed .submenu,
  .sidebar.closed .brand-wrap,
  .sidebar.closed .sidebar-footer {
    display: revert !important;
  }

  .sidebar.closed .menu-link {
    justify-content: flex-start;
    padding-left: 12px;
    padding-right: 12px;
  }

  .icon-navbar {
    display: inline-flex;
  }

  .title {
    font-size: 15px;
  }
}

@media (max-width: 768px) {
  .topbar {
    padding: 0 12px;
    gap: 10px;
  }

  .topbar-control {
    padding: 0 8px;
  }

  .user-info {
    display: none;
  }

  .main {
    padding: 12px;
  }

  .content-footer {
    padding: 0 12px;
    font-size: 11px;
  }
}

@media (max-width: 576px) {
  .topbar-brand-text {
    display: none;
  }

  .lang-label {
    display: none;
  }

  .topbar-control.lang-control {
    gap: 4px;
    padding: 0 6px;
  }

  .lang-pill {
    min-width: 38px;
    padding: 0 6px;
  }

  .content-footer-right {
    display: none;
  }

  .user-menu {
    right: -10px;
    min-width: 190px;
  }
}
</style>
