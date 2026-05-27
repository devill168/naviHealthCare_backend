<template>
    <div>
        <div class="finder-shell">
            <!-- Hero -->
            <section class="finder-hero">
                <div class="finder-hero-overlay"></div>
                <div class="row align-items-center g-3 position-relative">
                    <div class="col-lg-8">
                        <div class="hero-content">
                            <div class="hero-badge">
                                <i class="fa-solid fa-location-crosshairs me-2"></i>
                                {{ t("healthFacility.findLocationTitle") || "Health Facility Finder" }}
                            </div>

                            <h2 class="hero-title mb-2">
                                {{ t("healthFacility.findLocationTitle") || "Health Facility Finder" }}
                            </h2>

                            <p class="hero-subtitle mb-0">
                                {{
                                    t("healthFacility.findLocationSubtitle") ||
                                    "Filter health facilities by province, district, commune, village, and type."
                                }}
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="hero-stat-grid">
                            <div class="hero-stat-card">
                                <span class="stat-label">
                                    {{ t("healthFacility.results") || "Results" }}
                                </span>
                                <strong>{{ searched ? facilities.length : 0 }}</strong>
                            </div>

                            <div class="hero-stat-card">
                                <span class="stat-label">
                                    {{ t("healthFacility.status") || "Status" }}
                                </span>
                                <strong>
                                    {{
                                        loading
                                            ? (t("healthFacility.loading") || "Loading...")
                                            : (t("healthFacility.ready") || "Ready")
                                    }}
                                </strong>
                            </div>

                            <div class="hero-stat-card">
                                <span class="stat-label">
                                    {{ t("healthFacility.visitRecords") || "Visit Records" }}
                                </span>
                                <strong>{{ visitRecords.length }}</strong>
                            </div>

                            <div class="hero-stat-card">
                                <span class="stat-label">
                                    {{ t("healthFacility.myLocation") || "My Location" }}
                                </span>
                                <strong>{{ currentLocationStatus }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Search -->
            <section class="finder-card">
                <div class="finder-card-header border-0">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                        <div>
                            <h5 class="mb-1 fw-bold search-title">
                                <i class="fa-solid fa-magnifying-glass-location me-2 text-primary"></i>
                                {{ t("healthFacility.searchFilters") || "Search & Filters" }}
                            </h5>
                            <p class="text-muted mb-0 small">
                                {{
                                    t("healthFacility.filterByAdministrativeAreaAndType") ||
                                    "Filter by administrative area and health facility type"
                                }}
                            </p>
                        </div>

                        <div class="search-pill-info">
                            <i class="fa-solid fa-route me-2"></i>
                            {{ t("healthFacility.myLocation") || "My Location" }}:
                            <strong>{{ currentLocationStatus }}</strong>
                        </div>
                    </div>
                </div>

                <div class="finder-card-body pt-3">
                    <form @submit.prevent="findLocation">
                        <div class="filter-section-title">
                            <i class="fa-solid fa-filter me-2"></i>
                            {{ t("healthFacility.filters") || "Filters" }}
                        </div>

                        <div class="filter-control-shell">
                            <!-- Row 1 -->
                            <div class="row g-3 filter-row">
                                <div class="col-lg-4 col-md-6">
                                    <div class="filter-control-card">
                                        <label class="form-label">
                                            {{ t("healthFacility.type") || "Type" }}
                                        </label>
                                        <select v-model="form.type" class="form-select custom-input">
                                            <option value="">
                                                {{ t("healthFacility.allTypes") || "All Types" }}
                                            </option>
                                            <option v-for="type in facilityTypes" :key="type.value" :value="type.value">
                                                {{ type.label }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="filter-control-card">
                                        <label class="form-label">
                                            {{ t("healthFacility.province") || "Province" }}
                                        </label>
                                        <select v-model="form.province_code" class="form-select custom-input"
                                            @change="onProvinceChange">
                                            <option value="">
                                                {{ t("healthFacility.allProvinces") || "All Provinces" }}
                                            </option>
                                            <option v-for="province in provinces" :key="province.province_code"
                                                :value="province.province_code">
                                                {{ province.province_name_en || province.province_name_kh }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-12">
                                    <div class="filter-control-card">
                                        <label class="form-label">
                                            {{ t("healthFacility.district") || "District" }}
                                        </label>
                                        <select v-model="form.district_code" class="form-select custom-input"
                                            @change="onDistrictChange" :disabled="!form.province_code">
                                            <option value="">
                                                {{ t("healthFacility.allDistricts") || "All Districts" }}
                                            </option>
                                            <option v-for="district in filteredDistricts" :key="district.district_code"
                                                :value="district.district_code">
                                                {{ district.district_name_en || district.district_name_kh }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="row g-3 filter-row mt-1">
                                <div class="col-lg-4 col-md-6">
                                    <div class="filter-control-card">
                                        <label class="form-label">
                                            {{ t("healthFacility.commune") || "Commune" }}
                                        </label>
                                        <select v-model="form.commune_code" class="form-select custom-input"
                                            @change="onCommuneChange" :disabled="!form.district_code">
                                            <option value="">
                                                {{ t("healthFacility.allCommunes") || "All Communes" }}
                                            </option>
                                            <option v-for="commune in filteredCommunes" :key="commune.commune_code"
                                                :value="commune.commune_code">
                                                {{ commune.commune_name_en || commune.commune_name_kh }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="filter-control-card">
                                        <label class="form-label">
                                            {{ t("healthFacility.village") || "Village" }}
                                        </label>
                                        <select v-model="form.village_code" class="form-select custom-input"
                                            :disabled="!form.commune_code">
                                            <option value="">
                                                {{ t("healthFacility.allVillages") || "All Villages" }}
                                            </option>
                                            <option v-for="village in filteredVillages" :key="village.village_code"
                                                :value="village.village_code">
                                                {{ village.village_name_en || village.village_name_kh }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-12">
                                    <div class="filter-control-card action-card">
                                        <label class="form-label">
                                            {{ t("healthFacility.action") || "Action" }}
                                        </label>
                                        <div class="action-toolbar filter-action-toolbar">
                                            <button type="submit" class="btn btn-primary btn-action-primary"
                                                :disabled="loading">
                                                <span v-if="loading"
                                                    class="spinner-border spinner-border-sm me-2"></span>
                                                <i v-else class="fa-solid fa-magnifying-glass me-1"></i>
                                                {{ t("healthFacility.search") || "Search" }}
                                            </button>

                                            <button type="button" class="btn btn-light btn-action-secondary"
                                                @click="resetSearch" :disabled="loading || locationLoading">
                                                <i class="fa-solid fa-rotate-left me-1"></i>
                                                {{ t("healthFacility.reset") || "Reset" }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="search-summary compact" v-if="searched">
                        <div class="summary-item">
                            <span class="label">{{ t("healthFacility.type") || "Type" }}</span>
                            <strong>
                                {{
                                    form.type
                                        ? getTypeLabel(form.type)
                                        : (t("healthFacility.all") || "All")
                                }}
                            </strong>
                        </div>

                        <div class="summary-item">
                            <span class="label">{{ t("healthFacility.province") || "Province" }}</span>
                            <strong>{{ summaryProvince }}</strong>
                        </div>

                        <div class="summary-item">
                            <span class="label">{{ t("healthFacility.district") || "District" }}</span>
                            <strong>{{ summaryDistrict }}</strong>
                        </div>

                        <div class="summary-item">
                            <span class="label">{{ t("healthFacility.commune") || "Commune" }}</span>
                            <strong>{{ summaryCommune }}</strong>
                        </div>

                        <div class="summary-item">
                            <span class="label">{{ t("healthFacility.village") || "Village" }}</span>
                            <strong>{{ summaryVillage }}</strong>
                        </div>

                        <div class="summary-item summary-highlight">
                            <span class="label">{{ t("healthFacility.found") || "Found" }}</span>
                            <strong>
                                {{ facilities.length }} {{ t("healthFacility.facilities") || "facilities" }}
                            </strong>
                        </div>
                    </div>
                </div>
            </section>

            <!-- My Visit Records -->
            <section v-if="showVisitorHistory" class="finder-card visitor-history-card">
                <div class="finder-card-header border-0">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                        <div>
                            <h5 class="mb-1 fw-bold search-title">
                                <i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>
                                {{ t("healthFacility.myHealthFacilityVisitHistory") || "My Health Facility VisitHistory" }}
                            </h5>
                            <p class="text-muted mb-0 small">
                                {{
                                    t("healthFacility.visitRecordWillAppearHere") ||
                                    "Your latest health facility visit records will appear here."
                                }}
                            </p>
                        </div>

                        <button type="button" class="btn btn-light btn-action-secondary btn-sm-history"
                            :disabled="visitRecordsLoading" @click="loadVisitRecords">
                            <span v-if="visitRecordsLoading" class="spinner-border spinner-border-sm me-2"></span>
                            <i v-else class="fa-solid fa-rotate-right me-1"></i>
                            {{ t("healthFacility.refresh") || "Refresh" }}
                        </button>
                    </div>
                </div>

                <div class="finder-card-body pt-3">
                    <div v-if="visitRecordsLoading" class="loading-state loading-state-sm">
                        <div class="spinner"></div>
                        <div class="loading-text">
                            {{ t("healthFacility.loading") || "Loading..." }}
                        </div>
                    </div>

                    <div v-else-if="visitRecords.length === 0" class="empty-state compact-empty">
                        <div class="empty-icon">
                            <i class="fa-solid fa-calendar-xmark"></i>
                        </div>
                        <h6 class="mb-2">
                            {{ t("healthFacility.noVisitRecords") || "No visit records" }}
                        </h6>
                        <p class="text-muted mb-0 small">
                            {{
                                t("healthFacility.visitRecordWillAppearHere") ||
                                "Your latest health facility visit records will appear here."
                            }}
                        </p>
                    </div>

                    <template v-else>
                        <div class="pagination-summary">
                            <span>
                                {{ historyStartIndex }} - {{ historyEndIndex }}
                                / {{ visitRecords.length }}
                            </span>
                        </div>

                        <div class="row g-3">
                            <div v-for="record in paginatedVisitRecords" :key="record.id"
                                class="col-12 col-md-6 col-xl-4">
                                <div class="mini-card h-100">
                                    <div class="mini-card-top">
                                        <div class="mini-card-icon">
                                            <i class="fa-solid fa-hospital"></i>
                                        </div>
                                        <div class="mini-card-badges">
                                            <span class="visit-chip">
                                                <i class="fa-solid fa-calendar-days me-1"></i>
                                                {{ record.visit_date || "-" }}
                                            </span>
                                            <span class="visit-chip type" v-if="record.health_facility?.type">
                                                {{ getTypeLabel(record.health_facility.type) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mini-card-title">
                                        {{
                                            record.health_facility?.name_en ||
                                            record.health_facility?.name_km ||
                                            "-"
                                        }}
                                    </div>

                                    <div class="mini-card-subtitle" v-if="record.health_facility?.name_km">
                                        {{ record.health_facility.name_km }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="historyTotalPages > 1" class="pagination-wrap">
                            <button type="button" class="btn btn-page" @click="changeHistoryPage(historyPage - 1)"
                                :disabled="historyPage === 1">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>

                            <button v-for="page in historyVisiblePages" :key="`history-${page}`" type="button"
                                class="btn btn-page" :class="{ active: page === historyPage }"
                                @click="changeHistoryPage(page)">
                                {{ page }}
                            </button>

                            <button type="button" class="btn btn-page" @click="changeHistoryPage(historyPage + 1)"
                                :disabled="historyPage === historyTotalPages">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </section>

            <div v-if="errorMessage" class="alert alert-danger custom-alert mt-3">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                {{ errorMessage }}
            </div>

            <!-- Results -->
            <section v-if="searched" class="finder-results mt-4">
                <div class="results-topbar" v-if="facilities.length > 0">
                    <div>
                        <h5 class="mb-1 fw-bold">
                            {{ t("healthFacility.results") || "Results" }}
                        </h5>
                        <p class="text-muted small mb-0">
                            {{
                                t("healthFacility.showingResultsBySelectedFilters") ||
                                "Showing health facilities based on your selected filters"
                            }}
                        </p>
                    </div>

                    <div class="result-count-badge">
                        {{ facilities.length }}
                        {{
                            facilities.length > 1
                                ? (t("healthFacility.resultsPlural") || "results")
                                : (t("healthFacility.result") || "result")
                        }}
                    </div>
                </div>

                <div v-if="loading" class="loading-state">
                    <div class="spinner"></div>
                    <div class="loading-text">
                        {{ t("healthFacility.searchingHealthFacilities") || "Searching health facilities..." }}
                    </div>
                </div>

                <div v-else-if="facilities.length === 0" class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-solid fa-location-slash"></i>
                    </div>
                    <h5 class="mb-2">
                        {{ t("healthFacility.noHealthFacilitiesFound") || "No health facilities found" }}
                    </h5>
                    <p class="text-muted mb-0">
                        {{ t("healthFacility.tryAnotherFilterCombination") || "Try another filter combination." }}
                    </p>
                </div>

                <template v-else>
                    <div class="pagination-summary">
                        <span>
                            {{ resultStartIndex }} - {{ resultEndIndex }}
                            / {{ facilities.length }}
                        </span>
                    </div>

                    <div class="row g-3">
                        <div v-for="item in paginatedFacilities" :key="item.id" class="col-12 col-lg-6">
                            <div class="result-card h-100">
                                <div class="result-card-header">
                                    <div class="result-card-media">
                                        <div class="mini-card-icon facility-icon result-icon">
                                            <img v-if="item.hf_image_url" :src="item.hf_image_url"
                                                :alt="item.name_en || item.name_km" class="mini-facility-image"
                                                @error="handleImageError" />
                                            <i v-else class="fa-solid fa-hospital"></i>
                                        </div>
                                    </div>

                                    <div class="result-card-main">
                                        <div class="result-card-title">
                                            {{ item.name_en || item.name_km || "-" }}
                                        </div>

                                        <div v-if="item.name_km && item.name_km !== item.name_en"
                                            class="result-card-subtitle">
                                            {{ item.name_km }}
                                        </div>

                                        <div class="mini-card-badges mt-2">
                                            <span class="visit-chip">
                                                {{ getTypeLabel(item.type) }}
                                            </span>

                                            <span class="visit-chip"
                                                :class="item.is_active ? 'success-chip' : 'danger-chip'">
                                                {{
                                                    item.is_active
                                                        ? (t("healthFacility.active") || "Active")
                                                        : (t("healthFacility.inactive") || "Inactive")
                                                }}
                                            </span>

                                            <span
                                                v-if="item.distance_km !== null && item.distance_km !== undefined && item.distance_km !== ''"
                                                class="visit-chip muted">
                                                <i class="fa-solid fa-road me-1"></i>
                                                {{ formatDistance(item.distance_km) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="result-card-body">
                                    <div class="result-table-wrap">
                                        <table class="result-info-table table-sm">
                                            <tbody>
                                                <tr>
                                                    <th>{{ t("healthFacility.code") || "Code" }}</th>
                                                    <td>{{ item.code || "" }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ t("healthFacility.province") || "Province" }}</th>
                                                    <td>{{ item.province?.name_en || item.province?.name_kh || "" }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ t("healthFacility.district") || "District" }}</th>
                                                    <td>{{ item.district?.name_en || item.district?.name_kh || "" }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ t("healthFacility.commune") || "Commune" }}</th>
                                                    <td>{{ item.commune?.name_en || item.commune?.name_kh || "" }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ t("healthFacility.village") || "Village" }}</th>
                                                    <td>{{ item.village?.name_en || item.village?.name_kh || "" }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ t("healthFacility.coordinates") || "Coordinates" }}</th>
                                                    <td class="coordinate-text">
                                                        {{ item.latitude || "" }}, {{ item.longitude || "" }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mini-card-actions result-card-actions">
                                    <button type="button" class="btn btn-visit-facility" @click="openVisitModal(item)">
                                        <i class="fa-solid fa-user-check me-1"></i>
                                        {{ t("healthFacility.visitHealthFacility") || "Visit" }}
                                    </button>

                                    <button v-if="item.hf_image_url" type="button" class="btn btn-view-image"
                                        @click="openImagePreview(item)">
                                        <i class="fa-solid fa-image me-1"></i>
                                        {{ t("healthFacility.viewImage") || "View Image" }}
                                    </button>

                                    <a v-if="item.latitude && item.longitude" class="btn btn-direct-location"
                                        :href="googleDirectionLink(item.latitude, item.longitude)" target="_blank"
                                        rel="noopener noreferrer">
                                        <i class="fa-solid fa-location-arrow me-1"></i>
                                        {{ t("healthFacility.directLocation") || "Direction" }}
                                    </a>

                                    <button v-if="item.latitude && item.longitude"
                                        class="btn btn-outline-secondary btn-action-copy" type="button"
                                        @click="copyCoordinates(item)">
                                        <i class="fa-solid fa-copy me-1"></i>
                                        {{ t("healthFacility.copyCoordinates") || "Copy" }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="resultTotalPages > 1" class="pagination-wrap">
                        <button type="button" class="btn btn-page" @click="changeResultPage(resultPage - 1)"
                            :disabled="resultPage === 1">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>

                        <button v-for="page in resultVisiblePages" :key="`result-${page}`" type="button"
                            class="btn btn-page" :class="{ active: page === resultPage }"
                            @click="changeResultPage(page)">
                            {{ page }}
                        </button>

                        <button type="button" class="btn btn-page" @click="changeResultPage(resultPage + 1)"
                            :disabled="resultPage === resultTotalPages">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </template>
            </section>
        </div>

        <!-- Visit Modal -->
        <div v-if="showVisitModal" class="visit-modal-backdrop" @click.self="closeVisitModal">
            <div class="visit-modal">
                <div class="visit-modal-header">
                    <div>
                        <h5 class="mb-1 fw-bold">
                            <i class="fa-solid fa-user-check me-2 text-primary"></i>
                            {{ t("healthFacility.visitHealthFacility") || "Visit Health Facility" }}
                        </h5>
                        <p class="text-muted mb-0 small">
                            {{
                                t("healthFacility.confirmVisitAndSaveToDatabase") ||
                                "Confirm visit information and save to database"
                            }}
                        </p>
                    </div>

                    <button type="button" class="btn-close custom-close" @click="closeVisitModal"
                        :disabled="visitLoading"></button>
                </div>

                <div class="visit-modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">
                                {{ t("healthFacility.username") || "Username" }}
                            </label>
                            <input type="text" class="form-control custom-input" :value="visitForm.username" readonly />
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                {{ t("healthFacility.healthFacility") || "Health Facility" }}
                            </label>
                            <input type="text" class="form-control custom-input" :value="visitForm.health_facility_name"
                                readonly />
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                {{ t("healthFacility.visitDate") || "Visit Date" }}
                            </label>
                            <input v-model="visitForm.visit_date" type="date" class="form-control custom-input"
                                :disabled="visitLoading" />
                        </div>
                    </div>
                </div>

                <div class="visit-modal-footer">
                    <button type="button" class="btn btn-light btn-action-secondary" @click="closeVisitModal"
                        :disabled="visitLoading">
                        <i class="fa-solid fa-xmark me-1"></i>
                        {{ t("healthFacility.cancel") || "Cancel" }}
                    </button>

                    <button type="button" class="btn btn-primary btn-action-primary" @click="confirmVisit"
                        :disabled="visitLoading || !visitForm.user_id || !visitForm.health_facility_id || !visitForm.visit_date">
                        <span v-if="visitLoading" class="spinner-border spinner-border-sm me-2"></span>
                        <i v-else class="fa-solid fa-check me-1"></i>
                        {{ t("healthFacility.confirmVisit") || "Confirm Visit" }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Image Preview Modal -->
        <!-- Image Preview Modal -->
        <div v-if="showImageModal" class="image-preview-backdrop" @click.self="closeImagePreview">
            <div class="image-preview-modal">
                <div class="image-preview-header">
                    <h6 class="mb-0 fw-bold text-truncate">
                        <i class="fa-solid fa-image me-2 text-primary"></i>
                        {{ previewImageTitle }}
                    </h6>

                    <button type="button" class="btn-close custom-close" @click="closeImagePreview"></button>
                </div>

                <div class="image-preview-body">
                    <img v-if="previewImageUrl" :src="previewImageUrl" :alt="previewImageTitle"
                        class="image-preview-full" />

                    <div class="mt-3">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th style="width: 160px;">
                                        {{ t("healthFacility.directorName") || "Director Name" }}
                                    </th>
                                    <td>{{ previewDirectorName }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ t("healthFacility.contactPhone") || "Contact Phone" }}
                                    </th>
                                    <td>{{ previewContactPhone }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-toast" v-if="toast.show" :class="toast.type">
            <i class="fa-solid me-2"
                :class="toast.type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'"></i>
            {{ toast.message }}
        </div>
    </div>
</template>

<script>
import axios from "axios"
import { useI18n } from "vue-i18n"

export default {
    name: "HealthFacilityFindLocation",

    setup() {
        const { t, locale } = useI18n()
        return { t, locale }
    },

    data() {
        return {
            loading: false,
            locationLoading: false,
            visitLoading: false,
            visitRecordsLoading: false,
            searched: false,
            errorMessage: "",
            facilities: [],
            visitRecords: [],

            provinces: [],
            districts: [],
            communes: [],
            villages: [],

            filteredDistricts: [],
            filteredCommunes: [],
            filteredVillages: [],

            form: {
                type: "",
                province_code: "",
                district_code: "",
                commune_code: "",
                village_code: "",
                latitude: "",
                longitude: "",
                radius_km: 5,
            },

            showVisitModal: false,
            visitForm: {
                user_id: "",
                username: "",
                health_facility_id: "",
                health_facility_name: "",
                visit_date: "",
            },

            showImageModal: false,
            previewImageUrl: "",
            previewImageTitle: "",
            previewDirectorName: "",
            previewContactPhone: "",

            pagination: {
                result_page: 1,
                result_per_page: 12,
                history_page: 1,
                history_per_page: 12,
            },

            toast: {
                show: false,
                message: "",
                type: "success",
            },

            toastTimer: null,
        }
    },

    computed: {
        facilityTypes() {
            return [
                { value: "PHD", label: this.t("healthFacility.types.PHD") || "Provincial Health Department" },
                { value: "NH", label: this.t("healthFacility.types.NH") || "National Hospital" },
                { value: "PH", label: this.t("healthFacility.types.PH") || "Provincial Hospital" },
                { value: "OD", label: this.t("healthFacility.types.OD") || "Operational District" },
                { value: "RH", label: this.t("healthFacility.types.RH") || "Referral Hospital" },
                { value: "HC", label: this.t("healthFacility.types.HC") || "Health Center" },
                { value: "HP", label: this.t("healthFacility.types.HP") || "Health Post" },
            ]
        },

        resultPage() {
            return this.pagination.result_page
        },

        historyPage() {
            return this.pagination.history_page
        },

        showVisitorHistory() {
            return !this.searched && !this.hasAnyFilter
        },

        summaryProvince() {
            if (!this.form.province_code) return this.t("healthFacility.all") || "All"
            const item = this.provinces.find(
                (x) => String(x.province_code) === String(this.form.province_code)
            )
            return item ? item.province_name_en || item.province_name_kh || "-" : "-"
        },

        summaryDistrict() {
            if (!this.form.district_code) return this.t("healthFacility.all") || "All"
            const item = this.filteredDistricts.find(
                (x) => String(x.district_code) === String(this.form.district_code)
            )
            return item ? item.district_name_en || item.district_name_kh || "-" : "-"
        },

        summaryCommune() {
            if (!this.form.commune_code) return this.t("healthFacility.all") || "All"
            const item = this.filteredCommunes.find(
                (x) => String(x.commune_code) === String(this.form.commune_code)
            )
            return item ? item.commune_name_en || item.commune_name_kh || "-" : "-"
        },

        summaryVillage() {
            if (!this.form.village_code) return this.t("healthFacility.all") || "All"
            const item = this.filteredVillages.find(
                (x) => String(x.village_code) === String(this.form.village_code)
            )
            return item ? item.village_name_en || item.village_name_kh || "-" : "-"
        },

        hasAnyFilter() {
            return Boolean(
                this.form.type ||
                this.form.province_code ||
                this.form.district_code ||
                this.form.commune_code ||
                this.form.village_code
            )
        },

        hasCurrentLocation() {
            return Boolean(this.form.latitude && this.form.longitude)
        },

        currentLocationStatus() {
            if (this.locationLoading) return this.t("healthFacility.detecting") || "Detecting..."
            if (this.hasCurrentLocation) return this.t("healthFacility.ready") || "Ready"
            return this.t("healthFacility.unavailable") || "Unavailable"
        },

        resultTotalPages() {
            return Math.max(1, Math.ceil(this.facilities.length / this.pagination.result_per_page))
        },

        historyTotalPages() {
            return Math.max(1, Math.ceil(this.visitRecords.length / this.pagination.history_per_page))
        },

        paginatedFacilities() {
            const start = (this.pagination.result_page - 1) * this.pagination.result_per_page
            const end = start + this.pagination.result_per_page
            return this.facilities.slice(start, end)
        },

        paginatedVisitRecords() {
            const start = (this.pagination.history_page - 1) * this.pagination.history_per_page
            const end = start + this.pagination.history_per_page
            return this.visitRecords.slice(start, end)
        },

        resultStartIndex() {
            if (!this.facilities.length) return 0
            return (this.pagination.result_page - 1) * this.pagination.result_per_page + 1
        },

        resultEndIndex() {
            if (!this.facilities.length) return 0
            return Math.min(
                this.pagination.result_page * this.pagination.result_per_page,
                this.facilities.length
            )
        },

        historyStartIndex() {
            if (!this.visitRecords.length) return 0
            return (this.pagination.history_page - 1) * this.pagination.history_per_page + 1
        },

        historyEndIndex() {
            if (!this.visitRecords.length) return 0
            return Math.min(
                this.pagination.history_page * this.pagination.history_per_page,
                this.visitRecords.length
            )
        },

        resultVisiblePages() {
            return this.getVisiblePages(this.pagination.result_page, this.resultTotalPages)
        },

        historyVisiblePages() {
            return this.getVisiblePages(this.pagination.history_page, this.historyTotalPages)
        },
    },

    watch: {
        facilities() {
            this.ensureValidPages()
        },
        visitRecords() {
            this.ensureValidPages()
        },
    },

    async mounted() {
        await Promise.all([
            this.loadProvinces(),
            this.loadDistricts(),
            this.loadCommunes(),
            this.loadVillages(),
        ])

        this.getCurrentLocation(false)
        await this.loadVisitRecords()
    },

    methods: {
        getTypeLabel(type) {
            if (!type) return "-"
            return this.t(`healthFacility.types.${type}`) || type
        },

        getToday() {
            return new Date().toISOString().slice(0, 10)
        },

        getCurrentUser() {
            const keys = ["user", "auth_user", "register_user"]

            for (const key of keys) {
                const raw = localStorage.getItem(key)
                if (!raw) continue

                try {
                    const parsed = JSON.parse(raw)
                    if (parsed && parsed.id) return parsed
                } catch (error) {
                    // ignore invalid json
                }
            }

            return null
        },

        getVisiblePages(current, total) {
            const pages = []
            let start = Math.max(1, current - 2)
            let end = Math.min(total, current + 2)

            if (current <= 3) {
                end = Math.min(total, 5)
            }

            if (current >= total - 2) {
                start = Math.max(1, total - 4)
            }

            for (let i = start; i <= end; i++) {
                pages.push(i)
            }

            return pages
        },

        changeResultPage(page) {
            if (page < 1 || page > this.resultTotalPages) return
            this.pagination.result_page = page

            this.$nextTick(() => {
                const resultsSection = document.querySelector(".finder-results")
                if (resultsSection) {
                    resultsSection.scrollIntoView({ behavior: "smooth", block: "start" })
                }
            })
        },

        changeHistoryPage(page) {
            if (page < 1 || page > this.historyTotalPages) return
            this.pagination.history_page = page
        },

        resetAllPages() {
            this.pagination.result_page = 1
            this.pagination.history_page = 1
        },

        ensureValidPages() {
            if (this.pagination.result_page < 1) {
                this.pagination.result_page = 1
            }
            if (this.pagination.history_page < 1) {
                this.pagination.history_page = 1
            }

            if (this.pagination.result_page > this.resultTotalPages) {
                this.pagination.result_page = this.resultTotalPages
            }
            if (this.pagination.history_page > this.historyTotalPages) {
                this.pagination.history_page = this.historyTotalPages
            }
        },

        async loadVisitRecords() {
            const user = this.getCurrentUser()
            if (!user || !user.id) return

            this.visitRecordsLoading = true

            try {
                const res = await axios.get("/health-facility-visitors", {
                    params: {
                        user_id: user.id,
                        per_page: 1000,
                    },
                })

                this.visitRecords = Array.isArray(res.data?.data) ? res.data.data : []
                this.pagination.history_page = 1
                this.ensureValidPages()
            } catch (error) {
                console.error(error.response?.data || error.message)
                this.visitRecords = []
                this.pagination.history_page = 1
            } finally {
                this.visitRecordsLoading = false
            }
        },

        openVisitModal(item) {
            const user = this.getCurrentUser()

            if (!user || !user.id) {
                this.showToast(
                    this.t("healthFacility.userNotDetected") || "User not detected.",
                    "error"
                )
                return
            }

            this.visitForm = {
                user_id: user.id,
                username:
                    user.name ||
                    user.username ||
                    user.full_name ||
                    user.email ||
                    `User #${user.id}`,
                health_facility_id: item.id,
                health_facility_name: item.name_en || item.name_km || item.code || "-",
                visit_date: this.getToday(),
            }

            this.showVisitModal = true
        },

        closeVisitModal() {
            if (this.visitLoading) return

            this.showVisitModal = false
            this.visitForm = {
                user_id: "",
                username: "",
                health_facility_id: "",
                health_facility_name: "",
                visit_date: "",
            }
        },

        async confirmVisit() {
            if (!this.visitForm.user_id) {
                this.showToast(
                    this.t("healthFacility.userNotDetected") || "User not detected.",
                    "error"
                )
                return
            }

            if (!this.visitForm.health_facility_id) {
                this.showToast(
                    this.t("healthFacility.healthFacilityNotSelected") || "Health facility not selected.",
                    "error"
                )
                return
            }

            if (!this.visitForm.visit_date) {
                this.showToast(
                    this.t("healthFacility.pleaseSelectVisitDate") || "Please select visit date.",
                    "error"
                )
                return
            }

            this.visitLoading = true

            try {
                await axios.post("/health-facility-visitors", {
                    user_id: this.visitForm.user_id,
                    health_facility_id: this.visitForm.health_facility_id,
                    visit_date: this.visitForm.visit_date,
                })

                this.showToast(
                    this.t("healthFacility.visitRecordedSuccessfully") || "Visit recorded successfully.",
                    "success"
                )

                this.showVisitModal = false

                const savedUserId = this.visitForm.user_id
                this.visitForm = {
                    user_id: savedUserId,
                    username: "",
                    health_facility_id: "",
                    health_facility_name: "",
                    visit_date: "",
                }

                await this.loadVisitRecords()
            } catch (error) {
                this.showToast(
                    error.response?.data?.message ||
                    this.t("healthFacility.failedToSaveVisit") ||
                    "Failed to save visit.",
                    "error"
                )
            } finally {
                this.visitLoading = false
            }
        },

        openImagePreview(item) {
            if (!item?.hf_image_url) return

            this.previewImageUrl = item.hf_image_url
            this.previewImageTitle = item.name_en || item.name_km || item.code || "Health Facility Image"
            this.previewDirectorName = item.director_name || "-"
            this.previewContactPhone = item.contact_phone || "-"
            this.showImageModal = true
        },

        closeImagePreview() {
            this.showImageModal = false
            this.previewImageUrl = ""
            this.previewImageTitle = ""
            this.previewDirectorName = ""
            this.previewContactPhone = ""
        },

        async loadProvinces() {
            try {
                const res = await axios.get("/get-provinces")
                this.provinces = Array.isArray(res.data?.data) ? res.data.data : []
            } catch (error) {
                console.log(error.response?.data || error.message)
            }
        },

        async loadDistricts() {
            try {
                const res = await axios.get("/get-districts")
                this.districts = Array.isArray(res.data?.data) ? res.data.data : []
            } catch (error) {
                console.log(error.response?.data || error.message)
            }
        },

        async loadCommunes() {
            try {
                const res = await axios.get("/get-communes")
                this.communes = Array.isArray(res.data?.data) ? res.data.data : []
            } catch (error) {
                console.log(error.response?.data || error.message)
            }
        },

        async loadVillages() {
            try {
                const res = await axios.get("/get-villages", {
                    params: { per_page: 5000 },
                })
                this.villages = Array.isArray(res.data?.data) ? res.data.data : []
            } catch (error) {
                console.log(error.response?.data || error.message)
            }
        },

        onProvinceChange() {
            this.searched = false
            this.facilities = []
            this.errorMessage = ""
            this.pagination.result_page = 1

            this.form.district_code = ""
            this.form.commune_code = ""
            this.form.village_code = ""

            this.filteredDistricts = this.districts.filter(
                (item) => String(item.province_code) === String(this.form.province_code)
            )

            this.filteredCommunes = []
            this.filteredVillages = []
        },

        onDistrictChange() {
            this.searched = false
            this.facilities = []
            this.errorMessage = ""
            this.pagination.result_page = 1

            this.form.commune_code = ""
            this.form.village_code = ""

            this.filteredCommunes = this.communes.filter(
                (item) => String(item.district_code) === String(this.form.district_code)
            )

            this.filteredVillages = []
        },

        onCommuneChange() {
            this.searched = false
            this.facilities = []
            this.errorMessage = ""
            this.pagination.result_page = 1

            this.form.village_code = ""

            this.filteredVillages = this.villages.filter(
                (item) => String(item.commune_code) === String(this.form.commune_code)
            )
        },

        async findLocation() {
            this.errorMessage = ""
            this.searched = true
            this.facilities = []
            this.pagination.result_page = 1

            if (!this.hasAnyFilter) {
                this.errorMessage =
                    this.t("healthFacility.pleaseSelectAtLeastOneFilter") ||
                    "Please select at least one filter: province, district, commune, village, or type."
                return
            }

            this.loading = true

            try {
                const params = {}

                if (this.form.type) {
                    params.type = this.form.type
                    params.healthFacilityType = this.form.type
                }

                if (this.form.province_code) params.province_code = this.form.province_code
                if (this.form.district_code) params.district_code = this.form.district_code
                if (this.form.commune_code) params.commune_code = this.form.commune_code
                if (this.form.village_code) params.village_code = this.form.village_code

                if (this.hasCurrentLocation) {
                    params.latitude = this.form.latitude
                    params.longitude = this.form.longitude
                    params.radius_km = this.form.radius_km || 5
                }

                const res = await axios.get("/health-facilities/find-location", { params })
                this.facilities = Array.isArray(res.data?.data) ? res.data.data : []
                this.ensureValidPages()
            } catch (error) {
                this.facilities = []
                this.errorMessage =
                    error.response?.data?.message ||
                    this.t("healthFacility.failedToFetchHealthFacilities") ||
                    "Failed to fetch health facilities."
            } finally {
                this.loading = false
            }
        },

        getCurrentLocation(showToast = true) {
            if (!navigator.geolocation) {
                if (showToast) {
                    this.showToast(
                        this.t("healthFacility.geolocationNotSupported") ||
                        "Geolocation is not supported by this browser.",
                        "error"
                    )
                }
                return
            }

            this.locationLoading = true

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.form.latitude = Number(position.coords.latitude).toFixed(6)
                    this.form.longitude = Number(position.coords.longitude).toFixed(6)
                    this.locationLoading = false

                    if (showToast) {
                        this.showToast(
                            this.t("healthFacility.currentLocationDetectedSuccessfully") ||
                            "Current location detected successfully.",
                            "success"
                        )
                    }
                },
                (error) => {
                    this.locationLoading = false

                    let message =
                        this.t("healthFacility.unableToGetCurrentLocation") ||
                        "Unable to get current location."

                    if (error.code === 1) {
                        message = this.t("healthFacility.permissionDenied") || "Permission denied."
                    }
                    if (error.code === 2) {
                        message = this.t("healthFacility.locationUnavailable") || "Location unavailable."
                    }
                    if (error.code === 3) {
                        message =
                            this.t("healthFacility.locationRequestTimeout") ||
                            "Location request timeout."
                    }

                    if (showToast) {
                        this.showToast(message, "error")
                    }
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0,
                }
            )
        },

        resetSearch() {
            this.form = {
                type: "",
                province_code: "",
                district_code: "",
                commune_code: "",
                village_code: "",
                latitude: "",
                longitude: "",
                radius_km: 5,
            }

            this.filteredDistricts = []
            this.filteredCommunes = []
            this.filteredVillages = []
            this.facilities = []
            this.searched = false
            this.errorMessage = ""
            this.resetAllPages()

            this.getCurrentLocation(false)
            this.loadVisitRecords()
        },

        formatDistance(value) {
            if (value === null || value === undefined || value === "") return "-"
            return `${Number(value).toFixed(3)} KM`
        },

        googleDirectionLink(destinationLat, destinationLng) {
            if (!destinationLat || !destinationLng) return "#"

            const destination = `${destinationLat},${destinationLng}`

            if (this.hasCurrentLocation) {
                const origin = `${this.form.latitude},${this.form.longitude}`
                return `https://www.google.com/maps/dir/?api=1&origin=${encodeURIComponent(origin)}&destination=${encodeURIComponent(destination)}&travelmode=driving`
            }

            return `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(destination)}&travelmode=driving`
        },

        async copyCoordinates(item) {
            if (!item.latitude || !item.longitude) {
                this.showToast(
                    this.t("healthFacility.noCoordinatesAvailable") || "No coordinates available.",
                    "error"
                )
                return
            }

            const text = `${item.latitude}, ${item.longitude}`

            try {
                await navigator.clipboard.writeText(text)
                this.showToast(
                    this.t("healthFacility.coordinatesCopiedSuccessfully") ||
                    "Coordinates copied successfully.",
                    "success"
                )
            } catch (error) {
                this.showToast(
                    this.t("healthFacility.failedToCopyCoordinates") ||
                    "Failed to copy coordinates.",
                    "error"
                )
            }
        },

        handleImageError(event) {
            event.target.style.display = "none"
        },

        showToast(message, type = "success") {
            this.toast.message = message
            this.toast.type = type
            this.toast.show = true

            clearTimeout(this.toastTimer)
            this.toastTimer = setTimeout(() => {
                this.toast.show = false
            }, 2500)
        },
    },

    beforeUnmount() {
        clearTimeout(this.toastTimer)
    },
}
</script>

<style scoped>
.health-finder-page {
    --finder-primary: #2d73ff;
    --finder-primary-dark: #1654d1;
    --finder-primary-soft: #eef4ff;
    --finder-success-soft: #e8f8ee;
    --finder-danger-soft: #fff2f2;
    --finder-violet: #7c3aed;
    --finder-violet-dark: #6d28d9;
    --finder-violet-soft: #f4f0ff;
    --finder-card: #ffffff;
    --finder-border: #e5ebf3;
    --finder-text: #1f2937;
    --finder-muted: #6b7280;
    --finder-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    --finder-shadow-soft: 0 8px 24px rgba(15, 23, 42, 0.06);

    padding-bottom: 1.5rem;
    font-family: "Khmer OS Battambang", sans-serif;
    background:
        radial-gradient(circle at top right, rgba(45, 115, 255, 0.08), transparent 22%),
        radial-gradient(circle at bottom left, rgba(22, 163, 74, 0.07), transparent 22%),
        #f6f8fc;
}

.finder-shell {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.finder-hero {
    position: relative;
    overflow: hidden;
    border-radius: 26px;
    padding: 1.5rem;
    background: linear-gradient(135deg, #1d64f2 0%, #3f7eff 45%, #78a6ff 100%);
    color: #fff;
    box-shadow: 0 18px 40px rgba(29, 100, 242, 0.18);
}

.finder-hero-overlay {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 15% 20%, rgba(255, 255, 255, 0.18), transparent 20%),
        radial-gradient(circle at 88% 10%, rgba(255, 255, 255, 0.14), transparent 18%);
    pointer-events: none;
}

.hero-content,
.hero-stat-grid {
    position: relative;
    z-index: 1;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 0.5rem 0.9rem;
    background: rgba(255, 255, 255, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.22);
    backdrop-filter: blur(8px);
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 0.8rem;
}

.hero-title {
    font-size: 1.8rem;
    line-height: 1.15;
    font-weight: 800;
    letter-spacing: -0.02em;
}

.hero-subtitle {
    max-width: 760px;
    font-size: 13px;
    color: rgba(255, 255, 255, 0.92);
}

.hero-stat-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.hero-stat-card {
    border-radius: 18px;
    padding: 0.9rem 1rem;
    background: rgba(255, 255, 255, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(6px);
}

.hero-stat-card .stat-label {
    display: block;
    font-size: 11px;
    opacity: 0.9;
    margin-bottom: 4px;
}

.hero-stat-card strong {
    font-size: 1.05rem;
    font-weight: 800;
}

.finder-card {
    background: rgba(255, 255, 255, 0.98);
    border: 1px solid var(--finder-border);
    border-radius: 24px;
    box-shadow: var(--finder-shadow);
    overflow: hidden;
}

.finder-card-header {
    padding: 1rem 1.1rem 0;
}

.finder-card-body {
    padding: 1rem 1.1rem 1.1rem;
}

.search-title {
    color: var(--finder-text);
}

.search-pill-info {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 0.5rem 0.85rem;
    background: #f8fbff;
    border: 1px solid #dce8ff;
    color: #3a5275;
    font-size: 12px;
    font-weight: 700;
}

.filter-section-title {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 0.42rem 0.82rem;
    margin-bottom: 0.85rem;
    background: var(--finder-primary-soft);
    border: 1px solid #dbe7ff;
    color: var(--finder-primary);
    font-size: 12px;
    font-weight: 800;
}

.filter-control-shell {
    border: 1px solid #e6edf8;
    border-radius: 22px;
    padding: 1rem;
    background:
        radial-gradient(circle at top right, rgba(45, 115, 255, 0.04), transparent 28%),
        linear-gradient(180deg, #fcfdff 0%, #ffffff 100%);
    box-shadow: var(--finder-shadow-soft);
}

.filter-row {
    margin-top: 0;
}

.filter-control-card {
    height: 100%;
    padding: 0.85rem 0.9rem;
    border: 1px solid #e6edf8;
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
}

.action-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.form-label {
    font-weight: 700;
    color: #344054;
    margin-bottom: 0.45rem;
    font-size: 12px;
}

.custom-input {
    min-height: 46px;
    border-radius: 14px;
    border: 1px solid #d7e0ec;
    font-size: 13px;
    box-shadow: none;
    background: #fff;
}

.custom-input:focus {
    border-color: #8ab7ff;
    box-shadow: 0 0 0 0.16rem rgba(45, 115, 255, 0.12);
}

.action-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.filter-action-toolbar {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.btn-action-primary,
.btn-action-secondary {
    min-height: 46px;
    border-radius: 14px;
    padding: 0.6rem 0.95rem;
    font-weight: 700;
    font-size: 13px;
    transition: all 0.2s ease;
}

.btn-action-primary {
    box-shadow: 0 8px 18px rgba(45, 115, 255, 0.16);
}

.btn-action-secondary {
    border: 1px solid #d7deea;
    background: #fff;
}

.btn-action-secondary:hover {
    border-color: #c8d4e5;
    background: #f8fafc;
}

.search-summary {
    display: grid;
    gap: 10px;
    margin-top: 1rem;
}

.search-summary.compact {
    grid-template-columns: repeat(6, minmax(0, 1fr));
}

.summary-item {
    border-radius: 16px;
    padding: 0.75rem 0.85rem;
    background: linear-gradient(180deg, #fbfdff 0%, #ffffff 100%);
    border: 1px solid #e6edf8;
    box-shadow: var(--finder-shadow-soft);
}

.summary-item .label {
    display: block;
    font-size: 11px;
    color: var(--finder-muted);
    margin-bottom: 3px;
}

.summary-item strong {
    color: var(--finder-text);
    font-size: 12px;
    line-height: 1.35;
}

.summary-highlight {
    background: linear-gradient(135deg, #eef5ff, #f9fbff);
    border-color: #dce8ff;
}

.results-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 0.9rem;
    flex-wrap: wrap;
}

.result-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 88px;
    padding: 0.55rem 0.9rem;
    border-radius: 999px;
    background: var(--finder-primary-soft);
    color: var(--finder-primary);
    border: 1px solid #dbe7ff;
    font-size: 12px;
    font-weight: 800;
}

.loading-state,
.empty-state {
    background: #fff;
    border: 1px solid var(--finder-border);
    border-radius: 22px;
    padding: 2rem 1rem;
    text-align: center;
    box-shadow: var(--finder-shadow-soft);
}

.loading-state-sm {
    padding: 1.25rem 1rem;
}

.compact-empty {
    padding: 1.25rem 1rem;
}

.empty-icon {
    width: 72px;
    height: 72px;
    border-radius: 999px;
    background: linear-gradient(135deg, #eef4ff, #f5f8ff);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--finder-primary);
    font-size: 1.7rem;
    margin: 0 auto 0.9rem;
}

.spinner {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 5px solid #e5e7eb;
    border-top-color: var(--finder-primary);
    animation: spin 0.8s linear infinite;
    margin: 0 auto 1rem;
}

.loading-text {
    font-weight: 700;
    color: #475467;
    font-size: 13px;
}

.visitor-history-card {
    background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
}

.btn-sm-history {
    min-height: 40px;
    padding: 0.5rem 0.8rem;
}

.pagination-summary {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 0.85rem;
    font-size: 12px;
    color: var(--finder-muted);
    font-weight: 700;
}

.pagination-wrap {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 1rem;
}

.btn-page {
    min-width: 40px;
    height: 40px;
    border-radius: 12px;
    border: 1px solid #d7deea;
    background: #fff;
    color: #334155;
    font-weight: 700;
    box-shadow: var(--finder-shadow-soft);
    transition: all 0.2s ease;
}

.btn-page:hover:not(:disabled) {
    border-color: #b9cffb;
    color: var(--finder-primary);
}

.btn-page.active {
    background: linear-gradient(135deg, #2d73ff, #4b8bff);
    color: #fff;
    border-color: transparent;
}

.btn-page:disabled {
    opacity: 0.55;
    cursor: not-allowed;
}

.mini-card {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    padding: 0.95rem;
    border: 1px solid #e6edf8;
    border-radius: 20px;
    background: linear-gradient(180deg, #fbfdff 0%, #ffffff 100%);
    box-shadow: var(--finder-shadow-soft);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    height: 100%;
}

.mini-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 30px rgba(15, 23, 42, 0.08);
}

.mini-card-top {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.mini-card-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    background: var(--finder-primary-soft);
    color: var(--finder-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
    overflow: hidden;
    border: 1px solid #dbe7ff;
}

.facility-icon {
    background: linear-gradient(135deg, #f8fafc, #eef2ff);
}

.mini-facility-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.mini-card-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    min-width: 0;
}

.mini-card-title {
    font-size: 15px;
    line-height: 1.35;
    font-weight: 800;
    color: #111827;
    min-height: 40px;
}

.mini-card-subtitle {
    font-size: 12px;
    color: #6b7280;
    line-height: 1.35;
    min-height: 18px;
}

.mini-card-meta {
    display: grid;
    gap: 8px;
}

.mini-meta-label {
    display: block;
    font-size: 11px;
    color: var(--finder-muted);
    margin-bottom: 3px;
}

.mini-card-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: auto;
}

.visit-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.34rem 0.62rem;
    border-radius: 999px;
    background: #eef4ff;
    color: #245fdb;
    font-size: 11px;
    font-weight: 700;
}

.visit-chip.muted {
    background: #f5f7fb;
    color: #667085;
}

.visit-chip.type,
.success-chip {
    background: #eefaf1;
    color: #15803d;
}

.danger-chip {
    background: #fff2f2;
    color: #dc3545;
}

.btn-visit-facility,
.btn-direct-location,
.btn-action-copy,
.btn-view-image {
    width: 100%;
    min-height: 42px;
    border-radius: 14px;
    font-weight: 700;
    padding: 0.6rem 0.9rem;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    transition: all 0.22s ease;
    text-decoration: none;
}

.btn-visit-facility {
    background: linear-gradient(135deg, #16a34a, #22c55e);
    border: none;
    color: #fff;
    box-shadow: 0 10px 20px rgba(34, 197, 94, 0.18);
}

.btn-visit-facility:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 14px 24px rgba(34, 197, 94, 0.22);
}

/* updated view image button */
.btn-view-image {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 52%, #a78bfa 100%);
    border: none;
    color: #fff;
    box-shadow: 0 10px 24px rgba(124, 58, 237, 0.2);
}

.btn-view-image::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, transparent 20%, rgba(255, 255, 255, 0.28) 50%, transparent 80%);
    transform: translateX(-120%);
    transition: transform 0.55s ease;
}

.btn-view-image:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 16px 30px rgba(124, 58, 237, 0.26);
}

.btn-view-image:hover::before {
    transform: translateX(120%);
}

.btn-view-image:focus,
.btn-view-image:active {
    color: #fff;
    box-shadow:
        0 16px 30px rgba(124, 58, 237, 0.24),
        0 0 0 0.18rem rgba(139, 92, 246, 0.18);
}

.btn-view-image i,
.btn-view-image span,
.btn-view-image {
    position: relative;
    z-index: 1;
}

.btn-direct-location {
    background: linear-gradient(135deg, #2d73ff, #4b8bff);
    border: none;
    color: #fff;
    box-shadow: 0 10px 20px rgba(45, 115, 255, 0.18);
}

.btn-direct-location:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 14px 24px rgba(45, 115, 255, 0.22);
}

.btn-action-copy {
    background: #fff;
    border: 1px solid #dbe3ef;
    color: #344054;
}

.btn-action-copy:hover {
    background: #f8fafc;
    border-color: #c7d3e3;
    color: #1f2937;
}

/* result card */
.result-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    border-radius: 24px;
    border: 1px solid #dfe8f5;
    background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.result-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 36px rgba(15, 23, 42, 0.1);
}

.result-card-header {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    padding: 1rem 1rem 0.85rem;
    border-bottom: 1px solid #edf2f7;
    background:
        radial-gradient(circle at top right, rgba(45, 115, 255, 0.06), transparent 28%),
        linear-gradient(180deg, #fbfdff 0%, #ffffff 100%);
}

.result-card-media {
    flex-shrink: 0;
}

.result-icon {
    width: 60px;
    height: 60px;
    border-radius: 18px;
}

.result-card-main {
    min-width: 0;
    flex: 1;
}

.result-card-title {
    font-size: 1rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.4;
    word-break: break-word;
}

.result-card-subtitle {
    margin-top: 0.22rem;
    font-size: 12px;
    color: #64748b;
    line-height: 1.45;
    word-break: break-word;
}

.result-card-body {
    padding: 0.95rem 1rem 0.5rem;
}

.result-table-wrap {
    border: 1px solid #e5edf8;
    border-radius: 18px;
    overflow: hidden;
    background: #fff;
}

.result-info-table {
    width: 100%;
    margin: 0;
    border-collapse: collapse;
}

.result-info-table tbody tr:not(:last-child) {
    border-bottom: 1px solid #edf2f7;
}

.result-info-table tbody tr:nth-child(odd) {
    background: #fcfdff;
}

.result-info-table th,
.result-info-table td {
    padding: 0.78rem 0.9rem;
    vertical-align: top;
    font-size: 12px;
    line-height: 1.5;
}

.result-info-table th {
    width: 36%;
    min-width: 120px;
    background: #f8fbff;
    color: #475467;
    font-weight: 800;
    border-right: 1px solid #edf2f7;
    text-align: left;
    white-space: nowrap;
}

.result-info-table td {
    color: #111827;
    font-weight: 600;
    word-break: break-word;
}

.coordinate-text {
    font-family: inherit;
    color: #1d4ed8;
}

.result-card-actions {
    padding: 0 1rem 1rem;
    margin-top: auto;
}

.visit-modal-backdrop {
    position: fixed;
    inset: 0;
    z-index: 1050;
    background: rgba(15, 23, 42, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    backdrop-filter: blur(4px);
}

.visit-modal {
    width: 100%;
    max-width: 540px;
    background: #fff;
    border-radius: 22px;
    box-shadow: 0 25px 60px rgba(15, 23, 42, 0.25);
    overflow: hidden;
    border: 1px solid #e5ebf3;
}

.visit-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    padding: 1rem 1.1rem;
    border-bottom: 1px solid #edf2f7;
    background: linear-gradient(180deg, #fbfdff 0%, #ffffff 100%);
}

.visit-modal-body {
    padding: 1rem 1.1rem;
}

.visit-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 0.9rem 1.1rem 1.1rem;
    border-top: 1px solid #edf2f7;
    background: #fff;
}

.custom-close {
    box-shadow: none !important;
}

/* updated image preview modal */
.image-preview-backdrop {
    position: fixed;
    inset: 0;
    z-index: 1060;
    background: rgba(15, 23, 42, 0.68);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 18px;
    backdrop-filter: blur(6px);
}

.image-preview-modal {
    width: 100%;
    max-width: 920px;
    max-height: 90vh;
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 28px 70px rgba(15, 23, 42, 0.3);
    border: 1px solid #e5ebf3;
    display: flex;
    flex-direction: column;
    animation: modalPop 0.22s ease;
}

.image-preview-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 1rem 1.15rem;
    border-bottom: 1px solid #edf2f7;
    background:
        radial-gradient(circle at top right, rgba(124, 58, 237, 0.08), transparent 30%),
        linear-gradient(180deg, #fcfbff 0%, #ffffff 100%);
}

.image-preview-body {
    padding: 1.1rem;
    overflow: auto;
    display: block;
    background:
        linear-gradient(180deg, #f8fafc 0%, #f4f7fb 100%);
}

.image-preview-full {
    display: block;
    max-width: 100%;
    max-height: 68vh;
    width: auto;
    margin: 0 auto;
    object-fit: contain;
    border-radius: 18px;
    box-shadow: 0 14px 32px rgba(15, 23, 42, 0.14);
    background: #fff;
    border: 1px solid #eef2f7;
}

.image-preview-body .table {
    margin-bottom: 0;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
}

.image-preview-body .table th,
.image-preview-body .table td {
    padding: 0.85rem 0.95rem;
    vertical-align: middle;
    font-size: 13px;
}

.image-preview-body .table th {
    background: #faf7ff;
    color: #5b4b8a;
    font-weight: 800;
    border-color: #ede7fb;
}

.image-preview-body .table td {
    color: #1f2937;
    font-weight: 600;
}

.custom-alert {
    border-radius: 16px;
    font-weight: 600;
    box-shadow: var(--finder-shadow-soft);
}

.custom-toast {
    position: fixed;
    right: 20px;
    bottom: 20px;
    z-index: 9999;
    display: inline-flex;
    align-items: center;
    padding: 0.85rem 0.95rem;
    border-radius: 14px;
    color: #fff;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.18);
    font-weight: 700;
    animation: toastSlide 0.2s ease;
}

.custom-toast.success {
    background: #198754;
}

.custom-toast.error {
    background: #dc3545;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

@keyframes toastSlide {
    from {
        transform: translateY(12px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes modalPop {
    from {
        opacity: 0;
        transform: translateY(14px) scale(0.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@media (max-width: 1399.98px) {
    .search-summary.compact {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media (max-width: 991.98px) {
    .hero-title {
        font-size: 1.55rem;
    }

    .search-summary.compact {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .result-info-table th {
        width: 40%;
    }

    .filter-action-toolbar {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 767.98px) {
    .finder-hero {
        padding: 1rem;
    }

    .finder-card-header,
    .finder-card-body {
        padding-left: 0.9rem;
        padding-right: 0.9rem;
    }

    .hero-stat-grid {
        grid-template-columns: 1fr;
    }

    .search-summary.compact {
        grid-template-columns: 1fr;
    }

    .filter-control-shell {
        padding: 0.85rem;
    }

    .filter-control-card {
        padding: 0.8rem;
    }

    .filter-action-toolbar {
        grid-template-columns: 1fr;
    }

    .action-toolbar .btn,
    .pagination-wrap .btn {
        width: auto;
    }

    .visit-modal-footer {
        flex-direction: column;
    }

    .visit-modal-footer .btn {
        width: 100%;
    }

    .custom-toast {
        left: 16px;
        right: 16px;
        bottom: 16px;
    }

    .pagination-summary {
        justify-content: center;
    }

    .result-card-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .result-icon {
        width: 56px;
        height: 56px;
    }

    .result-info-table,
    .result-info-table tbody,
    .result-info-table tr,
    .result-info-table th,
    .result-info-table td {
        display: block;
        width: 100%;
    }

    .result-info-table tr {
        border-bottom: 1px solid #edf2f7;
    }

    .result-info-table tr:last-child {
        border-bottom: none;
    }

    .result-info-table th {
        border-right: none;
        border-bottom: 1px dashed #e7eef8;
        white-space: normal;
        padding-bottom: 0.45rem;
    }

    .result-info-table td {
        padding-top: 0.55rem;
    }

    .image-preview-modal {
        max-width: 100%;
        max-height: 92vh;
        border-radius: 20px;
    }

    .image-preview-header {
        padding: 0.9rem 1rem;
    }

    .image-preview-body {
        padding: 0.9rem;
    }

    .image-preview-full {
        max-height: 52vh;
        border-radius: 14px;
    }

    .image-preview-body .table th,
    .image-preview-body .table td {
        font-size: 12px;
        padding: 0.75rem 0.8rem;
    }
}
</style>
