<template>
    <div class="dashboard-page">
        <div class="dashboard-orb orb-1"></div>
        <div class="dashboard-orb orb-2"></div>
        <div class="dashboard-grid"></div>

        <div class="container-fluid py-4 dashboard-container">
            <!-- Top Hero -->
            <div class="top-hero mb-4">
                <div class="row g-4 align-items-center">
                    <div class="col-xl-8">
                        <div class="hero-panel h-100">
                            <div class="hero-panel__content">
                                <div class="hero-brand">
                                    <div class="hero-logo-wrap">
                                        <img
                                            src="@/assets/moh_logo.jpg"
                                            alt="Logo MOH"
                                            class="hero-logo"
                                        />
                                    </div>

                                    <div class="hero-text">
                                        <div class="hero-pill">
                                            <i class="fas fa-chart-pie me-2"></i>
                                            {{ $t("healthDashboard.heroPill") }}
                                        </div>
                                        <h1 class="hero-title">
                                            {{ $t("healthDashboard.heroTitle") }}
                                        </h1>
                                        <p class="hero-subtitle mb-0">
                                            {{ $t("healthDashboard.heroSubtitle") }}
                                        </p>
                                    </div>
                                </div>

                                <div class="hero-mini-stats">
                                    <div class="mini-stat-card">
                                        <div class="mini-stat-label">
                                            {{ $t("healthDashboard.topType") }}
                                        </div>
                                        <div class="mini-stat-value">
                                            {{ topType.key }}
                                        </div>
                                        <div class="mini-stat-sub">
                                            {{ topType.label }}
                                        </div>
                                    </div>

                                    <div class="mini-stat-card">
                                        <div class="mini-stat-label">
                                            {{ $t("healthDashboard.typesInUse") }}
                                        </div>
                                        <div class="mini-stat-value">
                                            {{ usedTypeCount }}
                                        </div>
                                        <div class="mini-stat-sub">
                                            {{ $t("healthDashboard.categoriesWithRecords") }}
                                        </div>
                                    </div>

                                    <div class="mini-stat-card">
                                        <div class="mini-stat-label">
                                            {{ $t("healthDashboard.activeRate") }}
                                        </div>
                                        <div class="mini-stat-value">
                                            {{ activeRate }}%
                                        </div>
                                        <div class="mini-stat-sub">
                                            {{ $t("healthDashboard.currentActiveFacilities") }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="action-panel h-100">
                            <div class="action-header">
                                <div>
                                    <div class="panel-eyebrow">
                                        {{ $t("healthDashboard.systemStatus") }}
                                    </div>
                                    <h5 class="panel-title mb-0">
                                        {{ $t("healthDashboard.liveDashboard") }}
                                    </h5>
                                </div>
                                <span class="live-badge">
                                    <span class="live-dot"></span>
                                    {{ $t("healthDashboard.live") }}
                                </span>
                            </div>

                            <div class="time-box">
                                <div class="time-label">
                                    {{ $t("healthDashboard.lastUpdated") }}
                                </div>
                                <div class="time-value">{{ currentDateTime }}</div>
                            </div>

                            <div class="action-stack">
                                <button
                                    class="btn btn-refresh-modern w-100"
                                    :disabled="loading"
                                    @click="loadDashboard"
                                >
                                    <span
                                        v-if="loading"
                                        class="spinner-border spinner-border-sm me-2"
                                    ></span>
                                    <i v-else class="fas fa-rotate-right me-2"></i>
                                    {{ $t("healthDashboard.refreshDashboard") }}
                                </button>
                            </div>

                            <div class="status-boxes">
                                <div class="status-box">
                                    <div class="status-box__label">
                                        {{ $t("healthDashboard.total") }}
                                    </div>
                                    <div class="status-box__value">
                                        {{ formatNumber(animatedStats.total_health_facilities) }}
                                    </div>
                                </div>
                                <div class="status-box success">
                                    <div class="status-box__label">
                                        {{ $t("healthDashboard.active") }}
                                    </div>
                                    <div class="status-box__value">
                                        {{ formatNumber(animatedStats.active_health_facilities) }}
                                    </div>
                                </div>
                                <div class="status-box danger">
                                    <div class="status-box__label">
                                        {{ $t("healthDashboard.inactive") }}
                                    </div>
                                    <div class="status-box__value">
                                        {{ formatNumber(animatedStats.inactive_health_facilities) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="row g-4 mb-4">
                <div
                    v-for="kpi in kpis"
                    :key="kpi.key"
                    class="col-12 col-md-6 col-xl-4"
                >
                    <div class="kpi-card h-100">
                        <div class="kpi-card__top">
                            <div class="kpi-icon" :class="kpi.iconClass">
                                <i :class="kpi.icon"></i>
                            </div>
                            <div class="kpi-badge">{{ kpi.badge }}</div>
                        </div>

                        <div class="kpi-card__body">
                            <div class="kpi-label">{{ kpi.label }}</div>
                            <div class="kpi-value">
                                {{ formatNumber(animatedStats[kpi.key]) }}
                            </div>
                            <div class="kpi-subtext">
                                {{ kpi.subtext }}
                            </div>
                        </div>

                        <div class="kpi-card__footer">
                            <div class="kpi-trend">
                                <i class="fas fa-wave-square me-2"></i>
                                {{ $t("healthDashboard.realTimeSummary") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="row g-4 mb-4">
                <div class="col-xl-5">
                    <div class="glass-card chart-card h-100">
                        <div class="card-header-modern">
                            <div>
                                <div class="panel-eyebrow">
                                    {{ $t("healthDashboard.visualization") }}
                                </div>
                                <h5 class="panel-title mb-1">
                                    {{ $t("healthDashboard.facilityDistributionByType") }}
                                </h5>
                                <p class="panel-desc mb-0">
                                    {{ $t("healthDashboard.activeFacilitiesGroupedByCategory") }}
                                </p>
                            </div>
                            <span class="chart-type-badge">
                                {{ $t("healthDashboard.pieChart") }}
                            </span>
                        </div>

                        <div class="chart-outer">
                            <div class="chart-wrap">
                                <canvas ref="pieCanvas"></canvas>
                            </div>
                        </div>

                        <div class="legend-grid">
                            <div
                                v-for="type in facilityTypes"
                                :key="type.key"
                                class="legend-chip"
                            >
                                <span
                                    class="legend-dot"
                                    :style="{ backgroundColor: type.color }"
                                ></span>
                                <span class="legend-key">{{ type.key }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="glass-card h-100">
                        <div class="card-header-modern">
                            <div>
                                <div class="panel-eyebrow">
                                    {{ $t("healthDashboard.breakdown") }}
                                </div>
                                <h5 class="panel-title mb-1">
                                    {{ $t("healthDashboard.facilityTypeSummary") }}
                                </h5>
                                <p class="panel-desc mb-0">
                                    {{ $t("healthDashboard.percentageAndTotalForEachType") }}
                                </p>
                            </div>
                            <span class="chart-type-badge">
                                {{ facilityTypes.length }} {{ $t("healthDashboard.types") }}
                            </span>
                        </div>

                        <div class="type-summary-list">
                            <div
                                v-for="type in facilityTypes"
                                :key="type.key"
                                class="type-summary-item"
                            >
                                <div class="type-summary-left">
                                    <div
                                        class="type-code-badge"
                                        :style="{ background: type.color }"
                                    >
                                        {{ type.key }}
                                    </div>

                                    <div class="type-content">
                                        <div class="type-title">
                                            {{ type.label }}
                                        </div>
                                        <div class="type-meta">
                                            {{ formatPercent(getTypePercent(type.key)) }}
                                            {{ $t("healthDashboard.ofActiveFacilities") }}
                                        </div>

                                        <div class="progress type-progress mt-2">
                                            <div
                                                class="progress-bar"
                                                role="progressbar"
                                                :style="{
                                                    width: getTypePercent(type.key) + '%',
                                                    background: type.color
                                                }"
                                            ></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="type-summary-right">
                                    {{ formatNumber(animatedTypeCounts[type.key]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Insight Cards -->
            <div class="row g-4">
                <div class="col-md-6 col-xl-4">
                    <div class="insight-card insight-card--blue h-100">
                        <div class="insight-card__icon">
                            <i class="fas fa-heart-circle-check"></i>
                        </div>
                        <div class="insight-card__title">
                            {{ $t("healthDashboard.activeFacilities") }}
                        </div>
                        <div class="insight-card__value">
                            {{ formatNumber(animatedStats.active_health_facilities) }}
                        </div>
                        <div class="insight-card__sub">
                            {{ activeRate }}%
                            {{ $t("healthDashboard.ofAllFacilityRecordsAreActive") }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="insight-card insight-card--cyan h-100">
                        <div class="insight-card__icon">
                            <i class="fas fa-building-shield"></i>
                        </div>
                        <div class="insight-card__title">
                            {{ $t("healthDashboard.mainFacilityType") }}
                        </div>
                        <div class="insight-card__value">
                            {{ topType.key }}
                        </div>
                        <div class="insight-card__sub">
                            {{ topType.label }}
                            {{ $t("healthDashboard.with") }}
                            {{ formatNumber(topType.total) }}
                            {{ $t("healthDashboard.facilities") }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="insight-card insight-card--violet h-100">
                        <div class="insight-card__icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="insight-card__title">
                            {{ $t("healthDashboard.coverageSummary") }}
                        </div>
                        <div class="insight-card__value">
                            {{ usedTypeCount }}
                        </div>
                        <div class="insight-card__sub">
                            {{ $t("healthDashboard.facilityTypesCurrentlyHaveRecords") }}
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="errorMessage" class="alert alert-danger mt-4 modern-alert">
                <i class="fas fa-circle-exclamation me-2"></i>
                {{ errorMessage }}
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios"
import { Chart } from "chart.js/auto"

export default {
    name: "HealthFacilityDashboard",

    data() {
        return {
            loading: false,
            errorMessage: "",
            pieChart: null,

            stats: {
                total_health_facilities: 0,
                active_health_facilities: 0,
                inactive_health_facilities: 0,
            },

            activeByType: {
                PHD: 0,
                NH: 0,
                PH: 0,
                OD: 0,
                RH: 0,
                HC: 0,
                HP: 0,
            },

            animatedStats: {
                total_health_facilities: 0,
                active_health_facilities: 0,
                inactive_health_facilities: 0,
            },

            animatedTypeCounts: {
                PHD: 0,
                NH: 0,
                PH: 0,
                OD: 0,
                RH: 0,
                HC: 0,
                HP: 0,
            },

            facilityTypes: [
                { key: "PHD", label: "", color: "#2563eb" },
                { key: "NH", label: "", color: "#0891b2" },
                { key: "PH", label: "", color: "#14b8a6" },
                { key: "OD", label: "", color: "#8b5cf6" },
                { key: "RH", label: "", color: "#f59e0b" },
                { key: "HC", label: "", color: "#22c55e" },
                { key: "HP", label: "", color: "#ec4899" },
            ],
        }
    },

    computed: {
        kpis() {
            return [
                {
                    key: "total_health_facilities",
                    label: this.$t("healthDashboard.totalFacilities"),
                    icon: "fas fa-hospital",
                    iconClass: "icon-soft-blue",
                    badge: this.$t("healthDashboard.overview"),
                    subtext: this.$t("healthDashboard.allHealthFacilityRecordsInSystem"),
                },
                {
                    key: "active_health_facilities",
                    label: this.$t("healthDashboard.activeFacilities"),
                    icon: "fas fa-circle-check",
                    iconClass: "icon-soft-green",
                    badge: this.$t("healthDashboard.status"),
                    subtext: this.$t("healthDashboard.recordsCurrentlyMarkedAsActive"),
                },
                {
                    key: "inactive_health_facilities",
                    label: this.$t("healthDashboard.inactiveFacilities"),
                    icon: "fas fa-circle-xmark",
                    iconClass: "icon-soft-pink",
                    badge: this.$t("healthDashboard.status"),
                    subtext: this.$t("healthDashboard.recordsCurrentlyMarkedAsInactive"),
                },
            ]
        },

        currentDateTime() {
            return new Date().toLocaleString()
        },

        activeRate() {
            const total = Number(this.stats.total_health_facilities || 0)
            const active = Number(this.stats.active_health_facilities || 0)
            if (!total) return "0.00"
            return ((active / total) * 100).toFixed(2)
        },

        usedTypeCount() {
            return this.facilityTypes.filter(
                (item) => Number(this.activeByType[item.key] || 0) > 0
            ).length
        },

        topType() {
            let best = {
                key: "-",
                label: "-",
                total: 0,
            }

            this.facilityTypes.forEach((item) => {
                const total = Number(this.activeByType[item.key] || 0)
                if (total > best.total) {
                    best = {
                        key: item.key,
                        label: item.label,
                        total,
                    }
                }
            })

            return best
        },
    },

    mounted() {
        this.setFacilityTypeLabels()
        this.loadDashboard()
    },

    watch: {
        "$i18n.locale"() {
            this.setFacilityTypeLabels()
            this.$nextTick(() => {
                this.renderOrUpdatePie()
            })
        },
    },

    methods: {
        setFacilityTypeLabels() {
            this.facilityTypes = [
                {
                    key: "PHD",
                    label: this.$t("healthDashboard.facilityTypePHD"),
                    color: "#2563eb",
                },
                {
                    key: "NH",
                    label: this.$t("healthDashboard.facilityTypeNH"),
                    color: "#0891b2",
                },
                {
                    key: "PH",
                    label: this.$t("healthDashboard.facilityTypePH"),
                    color: "#14b8a6",
                },
                {
                    key: "OD",
                    label: this.$t("healthDashboard.facilityTypeOD"),
                    color: "#8b5cf6",
                },
                {
                    key: "RH",
                    label: this.$t("healthDashboard.facilityTypeRH"),
                    color: "#f59e0b",
                },
                {
                    key: "HC",
                    label: this.$t("healthDashboard.facilityTypeHC"),
                    color: "#22c55e",
                },
                {
                    key: "HP",
                    label: this.$t("healthDashboard.facilityTypeHP"),
                    color: "#ec4899",
                },
            ]
        },

        async loadDashboard() {
            this.loading = true
            this.errorMessage = ""

            try {
                const res = await axios.get("/dashboard")
                const payload = res?.data?.data ?? {}

                this.stats = {
                    total_health_facilities: Number(payload.total_health_facilities ?? 0),
                    active_health_facilities: Number(payload.active_health_facilities ?? 0),
                    inactive_health_facilities: Number(payload.inactive_health_facilities ?? 0),
                }

                this.activeByType = {
                    PHD: Number(payload.active_by_type?.PHD ?? 0),
                    NH: Number(payload.active_by_type?.NH ?? 0),
                    PH: Number(payload.active_by_type?.PH ?? 0),
                    OD: Number(payload.active_by_type?.OD ?? 0),
                    RH: Number(payload.active_by_type?.RH ?? 0),
                    HC: Number(payload.active_by_type?.HC ?? 0),
                    HP: Number(payload.active_by_type?.HP ?? 0),
                }

                this.animateAllStats()
                this.animateAllTypes()

                this.$nextTick(() => {
                    this.renderOrUpdatePie()
                })
            } catch (error) {
                console.error("Failed to load dashboard:", error)
                this.errorMessage =
                    error?.response?.data?.message ||
                    error?.message ||
                    this.$t("healthDashboard.failedToLoadDashboard")
            } finally {
                this.loading = false
            }
        },

        animateAllStats() {
            Object.keys(this.stats).forEach((key) => {
                this.animateNumber(this.animatedStats, key, this.stats[key])
            })
        },

        animateAllTypes() {
            Object.keys(this.activeByType).forEach((key) => {
                this.animateNumber(this.animatedTypeCounts, key, this.activeByType[key])
            })
        },

        animateNumber(targetObject, key, target) {
            const start = Number(targetObject[key] || 0)
            const end = Number(target || 0)
            const durationMs = 800
            const startTime = performance.now()

            const tick = (now) => {
                const t = Math.min(1, (now - startTime) / durationMs)
                const eased = 1 - Math.pow(1 - t, 3)

                targetObject[key] = Math.round(start + (end - start) * eased)

                if (t < 1) {
                    requestAnimationFrame(tick)
                }
            }

            requestAnimationFrame(tick)
        },

        renderOrUpdatePie() {
            const canvas = this.$refs.pieCanvas
            if (!canvas) return

            const labels = this.facilityTypes.map((item) => item.key)
            const values = this.facilityTypes.map((item) =>
                Number(this.activeByType[item.key] || 0)
            )
            const backgroundColors = this.facilityTypes.map((item) => item.color)

            const chartData = {
                labels,
                datasets: [
                    {
                        data: values,
                        backgroundColor: backgroundColors,
                        borderColor: "#ffffff",
                        borderWidth: 4,
                        hoverOffset: 8,
                    },
                ],
            }

            if (this.pieChart) {
                this.pieChart.data = chartData
                this.pieChart.update()
                return
            }

            this.pieChart = new Chart(canvas, {
                type: "pie",
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 900,
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const total = context.dataset.data.reduce((sum, value) => sum + value, 0)
                                    const value = Number(context.raw || 0)
                                    const percent = total ? ((value / total) * 100).toFixed(2) : "0.00"
                                    return `${context.label}: ${this.formatNumber(value)} (${percent}%)`
                                },
                            },
                        },
                    },
                },
            })
        },

        getTypePercent(typeKey) {
            const total = Number(this.stats.active_health_facilities || 0)
            const value = Number(this.activeByType[typeKey] || 0)
            if (!total) return 0
            return ((value / total) * 100).toFixed(2)
        },

        formatPercent(value) {
            return Number(value || 0).toFixed(2) + "%"
        },

        formatNumber(n) {
            return new Intl.NumberFormat().format(Number(n || 0))
        },
    },

    beforeUnmount() {
        if (this.pieChart) {
            this.pieChart.destroy()
            this.pieChart = null
        }
    },
}
</script>

<style scoped>
.dashboard-page {
    position: relative;
    min-height: 100vh;
    background:
        radial-gradient(circle at top left, rgba(37, 99, 235, 0.12), transparent 26%),
        radial-gradient(circle at bottom right, rgba(139, 92, 246, 0.12), transparent 28%),
        linear-gradient(180deg, #f7fbff 0%, #eef4ff 50%, #f8fbff 100%);
    overflow: hidden;
}

.dashboard-container {
    position: relative;
    z-index: 2;
}

.dashboard-grid {
    position: absolute;
    inset: 0;
    pointer-events: none;
    background-image:
        linear-gradient(rgba(148, 163, 184, 0.07) 1px, transparent 1px),
        linear-gradient(90deg, rgba(148, 163, 184, 0.07) 1px, transparent 1px);
    background-size: 26px 26px;
    mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.45), transparent 92%);
}

.dashboard-orb {
    position: absolute;
    border-radius: 999px;
    filter: blur(70px);
    opacity: 0.55;
    pointer-events: none;
}

.orb-1 {
    width: 280px;
    height: 280px;
    top: -50px;
    left: -80px;
    background: rgba(59, 130, 246, 0.25);
}

.orb-2 {
    width: 260px;
    height: 260px;
    bottom: 0;
    right: -60px;
    background: rgba(168, 85, 247, 0.20);
}

.top-hero {
    position: relative;
}

.hero-panel,
.action-panel {
    border-radius: 30px;
    background: rgba(255, 255, 255, 0.76);
    border: 1px solid rgba(255, 255, 255, 0.7);
    box-shadow: 0 25px 55px rgba(15, 23, 42, 0.08);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
}

.hero-panel {
    overflow: hidden;
    position: relative;
}

.hero-panel::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
        linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(30, 41, 59, 0.88) 44%, rgba(37, 99, 235, 0.82) 100%);
}

.hero-panel__content {
    position: relative;
    z-index: 2;
    padding: 32px;
}

.hero-brand {
    display: flex;
    gap: 20px;
    align-items: center;
    margin-bottom: 28px;
}

.hero-logo-wrap {
    width: 88px;
    height: 88px;
    border-radius: 26px;
    background: rgba(255, 255, 255, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.18);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.hero-logo {
    width: 58px;
    height: 58px;
    object-fit: contain;
}

.hero-text {
    min-width: 0;
}

.hero-pill {
    display: inline-flex;
    align-items: center;
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.10);
    color: #dbeafe;
    border: 1px solid rgba(255, 255, 255, 0.14);
    font-size: 12px;
    font-weight: 800;
    margin-bottom: 14px;
}

.hero-title {
    font-size: 34px;
    line-height: 1.12;
    font-weight: 900;
    letter-spacing: -0.03em;
    color: #ffffff;
    margin-bottom: 10px;
}

.hero-subtitle {
    max-width: 760px;
    color: rgba(255, 255, 255, 0.82);
    font-size: 14px;
    line-height: 1.8;
}

.hero-mini-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
}

.mini-stat-card {
    padding: 16px 18px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.10);
    border: 1px solid rgba(255, 255, 255, 0.12);
}

.mini-stat-label {
    font-size: 12px;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.72);
    margin-bottom: 8px;
}

.mini-stat-value {
    font-size: 24px;
    font-weight: 900;
    color: #ffffff;
    line-height: 1;
    margin-bottom: 6px;
}

.mini-stat-sub {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.76);
}

.action-panel {
    padding: 24px;
    height: 100%;
}

.action-header,
.card-header-modern {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;
}

.panel-eyebrow {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-weight: 800;
    color: #94a3b8;
    margin-bottom: 6px;
}

.panel-title {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
}

.panel-desc {
    font-size: 13px;
    color: #64748b;
    line-height: 1.7;
}

.live-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #bbf7d0;
}

.live-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22c55e;
    box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.16);
}

.time-box {
    margin-top: 18px;
    padding: 18px;
    border-radius: 20px;
    background: linear-gradient(135deg, #f8fbff 0%, #eef5ff 100%);
    border: 1px solid #e2e8f0;
}

.time-label {
    font-size: 12px;
    color: #64748b;
    font-weight: 700;
    margin-bottom: 8px;
}

.time-value {
    font-size: 18px;
    color: #0f172a;
    font-weight: 800;
    line-height: 1.4;
}

.action-stack {
    margin-top: 16px;
}

.btn-refresh-modern {
    min-height: 50px;
    border: 0;
    border-radius: 16px;
    font-weight: 800;
    color: #ffffff;
    background: linear-gradient(135deg, #0f172a 0%, #2563eb 100%);
    box-shadow: 0 18px 30px rgba(37, 99, 235, 0.18);
    transition: all 0.25s ease;
}

.btn-refresh-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 22px 36px rgba(37, 99, 235, 0.22);
}

.status-boxes {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-top: 18px;
}

.status-box {
    padding: 14px;
    border-radius: 18px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.status-box.success {
    background: #f0fdf4;
    border-color: #bbf7d0;
}

.status-box.danger {
    background: #fdf2f8;
    border-color: #fbcfe8;
}

.status-box__label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 8px;
}

.status-box__value {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
}

.kpi-card {
    height: 100%;
    padding: 22px;
    border-radius: 28px;
    background: rgba(255, 255, 255, 0.82);
    border: 1px solid rgba(226, 232, 240, 0.9);
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.06);
    backdrop-filter: blur(12px);
    transition: all 0.25s ease;
}

.kpi-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 28px 50px rgba(15, 23, 42, 0.10);
}

.kpi-card__top,
.kpi-card__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.kpi-card__top {
    margin-bottom: 18px;
}

.kpi-icon {
    width: 58px;
    height: 58px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.icon-soft-blue {
    color: #2563eb;
    background: rgba(37, 99, 235, 0.12);
}

.icon-soft-green {
    color: #16a34a;
    background: rgba(34, 197, 94, 0.12);
}

.icon-soft-pink {
    color: #db2777;
    background: rgba(236, 72, 153, 0.12);
}

.kpi-badge {
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.kpi-label {
    font-size: 14px;
    color: #64748b;
    font-weight: 700;
    margin-bottom: 8px;
}

.kpi-value {
    font-size: 38px;
    line-height: 1;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.04em;
    margin-bottom: 12px;
}

.kpi-subtext {
    font-size: 13px;
    color: #94a3b8;
    line-height: 1.7;
}

.kpi-card__footer {
    margin-top: 18px;
    padding-top: 16px;
    border-top: 1px dashed #e2e8f0;
}

.kpi-trend {
    font-size: 12px;
    color: #64748b;
    font-weight: 700;
}

.glass-card {
    padding: 24px;
    border-radius: 28px;
    background: rgba(255, 255, 255, 0.82);
    border: 1px solid rgba(226, 232, 240, 0.9);
    box-shadow: 0 22px 46px rgba(15, 23, 42, 0.06);
    backdrop-filter: blur(12px);
}

.chart-type-badge {
    display: inline-flex;
    align-items: center;
    padding: 7px 12px;
    border-radius: 999px;
    background: #eff6ff;
    color: #2563eb;
    font-size: 12px;
    font-weight: 800;
}

.chart-card {
    overflow: hidden;
}

.chart-outer {
    padding: 6px 0 0;
}

.chart-wrap {
    position: relative;
    height: 330px;
}

.legend-grid {
    margin-top: 12px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.legend-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 999px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.legend-key {
    font-size: 12px;
    font-weight: 700;
    color: #334155;
}

.type-summary-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.type-summary-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 16px 18px;
    border-radius: 22px;
    background: linear-gradient(135deg, #f8fbff 0%, #f1f6ff 100%);
    border: 1px solid #e6edf7;
    transition: all 0.22s ease;
}

.type-summary-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(37, 99, 235, 0.08);
}

.type-summary-left {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    min-width: 0;
    flex: 1;
}

.type-code-badge {
    width: 56px;
    min-width: 56px;
    height: 40px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 13px;
    font-weight: 800;
    box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.12);
}

.type-content {
    flex: 1;
    min-width: 0;
}

.type-title {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 4px;
}

.type-meta {
    font-size: 12px;
    color: #64748b;
}

.type-progress {
    height: 9px;
    border-radius: 999px;
    background: #e2e8f0;
    overflow: hidden;
}

.type-progress .progress-bar {
    border-radius: 999px;
}

.type-summary-right {
    font-size: 18px;
    font-weight: 900;
    color: #2563eb;
    white-space: nowrap;
}

.insight-card {
    border-radius: 28px;
    padding: 24px;
    color: #ffffff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 22px 46px rgba(15, 23, 42, 0.12);
}

.insight-card::after {
    content: "";
    position: absolute;
    width: 180px;
    height: 180px;
    top: -60px;
    right: -60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.10);
}

.insight-card--blue {
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #60a5fa 100%);
}

.insight-card--cyan {
    background: linear-gradient(135deg, #0f766e 0%, #0891b2 50%, #67e8f9 100%);
}

.insight-card--violet {
    background: linear-gradient(135deg, #6d28d9 0%, #7c3aed 50%, #c4b5fd 100%);
}

.insight-card__icon {
    width: 58px;
    height: 58px;
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 18px;
    background: rgba(255, 255, 255, 0.16);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.insight-card__title {
    position: relative;
    z-index: 1;
    font-size: 16px;
    font-weight: 800;
    margin-bottom: 10px;
}

.insight-card__value {
    position: relative;
    z-index: 1;
    font-size: 36px;
    line-height: 1;
    font-weight: 900;
    letter-spacing: -0.04em;
    margin-bottom: 10px;
}

.insight-card__sub {
    position: relative;
    z-index: 1;
    font-size: 13px;
    line-height: 1.8;
    color: rgba(255, 255, 255, 0.84);
}

.modern-alert {
    border-radius: 18px;
    border: 1px solid #fecaca;
    background: #fff1f2;
    color: #be123c;
}

@media (max-width: 1199.98px) {
    .hero-mini-stats {
        grid-template-columns: 1fr;
    }

    .status-boxes {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 991.98px) {
    .hero-brand {
        align-items: flex-start;
    }

    .hero-title {
        font-size: 28px;
    }
}

@media (max-width: 767.98px) {
    .hero-panel__content,
    .action-panel,
    .kpi-card,
    .glass-card,
    .insight-card {
        padding: 20px;
    }

    .hero-brand {
        flex-direction: column;
    }

    .hero-logo-wrap {
        width: 76px;
        height: 76px;
    }

    .hero-title {
        font-size: 24px;
    }

    .chart-wrap {
        height: 280px;
    }

    .type-summary-item {
        flex-direction: column;
        align-items: stretch;
    }

    .type-summary-right {
        text-align: right;
    }
}
</style>
