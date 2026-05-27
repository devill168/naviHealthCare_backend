<template>
    <div class="container-fluid health-facility-page mt-4">
        <div class="listing-card">
            <div class="listing-card-body">
                <div class="listing-toolbar">
                    <h3 class="listing-title mb-0">
                        {{ t("healthFacility.title") }}
                    </h3>

                    <div class="listing-actions">
                        <select class="form-select page-size-select" v-model.number="pagination.per_page"
                            @change="changePerPage">
                            <option :value="10">10</option>
                            <option :value="25">25</option>
                            <option :value="50">50</option>
                            <option :value="100">100</option>
                        </select>

                        <button class="btn btn-create" @click="openCreateModal">
                            <i class="fa-solid fa-circle-plus me-1"></i>
                            {{ t("healthFacility.createButton") }}
                        </button>
                    </div>
                </div>

                <div class="table-responsive custom-table-wrap">
                    <table class="table custom-list-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="70">#</th>
                                <th width="84">{{ t("healthFacility.image") }}</th>
                                <th>{{ t("healthFacility.code") }}</th>
                                <th>{{ t("healthFacility.nameEn") }}</th>
                                <th>{{ t("healthFacility.nameKh") }}</th>
                                <th>{{ t("healthFacility.type") }}</th>
                                <th>{{ t("healthFacility.parent") }}</th>
                                <th>{{ t("healthFacility.province") }}</th>
                                <th>{{ t("healthFacility.directorName") }}</th>
                                <th>{{ t("healthFacility.contactPhone") }}</th>
                                <th>{{ t("healthFacility.latitude") }}</th>
                                <th>{{ t("healthFacility.longitude") }}</th>
                                <th>{{ t("healthFacility.status") }}</th>
                                <th width="220" class="text-end">
                                    {{ t("healthFacility.action") }}
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(item, index) in healthFacilities" :key="item.id || index">
                                <td>{{ rowNumber(index) }}</td>

                                <td>
                                    <div class="table-image-wrap">
                                        <img v-if="resolveImageUrl(item)" :src="resolveImageUrl(item)"
                                            alt="health facility" class="table-thumb" @error="handleTableImageError" />
                                        <div v-else class="table-thumb table-thumb-placeholder">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ item.code || "-" }}</td>
                                <td>{{ item.name_en || "-" }}</td>
                                <td>{{ item.name_km || "-" }}</td>
                                <td>
                                    <span class="type-badge">
                                        {{ item.type || "-" }}
                                    </span>
                                </td>
                                <td>
                                    {{ item.parent?.name_en || item.parent?.name_km || "-" }}
                                </td>
                                <td>
                                    {{
                                        item.province?.name_en ||
                                        item.province?.name_kh ||
                                        item.province_code ||
                                        "-"
                                    }}
                                </td>
                                <td>{{ item.director_name || "-" }}</td>
                                <td>{{ item.contact_phone || "-" }}</td>
                                <td>{{ item.latitude || "-" }}</td>
                                <td>{{ item.longitude || "-" }}</td>
                                <td>
                                    <span class="status-pill"
                                        :class="item.is_active ? 'status-active' : 'status-inactive'">
                                        {{
                                            item.is_active
                                                ? t("healthFacility.active")
                                                : t("healthFacility.inactive")
                                        }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="table-action-group justify-content-end">
                                        <button class="btn btn-sm btn-edit" @click="openEditModal(item)">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>
                                            {{ t("healthFacility.edit") }}
                                        </button>

                                        <button class="btn btn-sm btn-delete" @click="askDelete(item)">
                                            <i class="fa-solid fa-trash me-1"></i>
                                            {{ t("healthFacility.delete") }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="healthFacilities.length === 0">
                                <td colspan="14" class="text-center text-muted py-4">
                                    {{ t("healthFacility.noData") }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="pagination.total > 0" class="listing-footer">
                    <div class="listing-info">
                        Showing {{ pagination.from }} to {{ pagination.to }} of
                        {{ pagination.total }} entries
                    </div>

                    <nav>
                        <ul class="pagination custom-pagination mb-0">
                            <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                                <button class="page-link" type="button" @click="goToPage(1)"
                                    :disabled="pagination.current_page === 1">
                                    First
                                </button>
                            </li>

                            <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                                <button class="page-link" type="button" @click="goToPage(pagination.current_page - 1)"
                                    :disabled="pagination.current_page === 1">
                                    Prev
                                </button>
                            </li>

                            <li v-for="page in visiblePages" :key="page" class="page-item"
                                :class="{ active: page === pagination.current_page }">
                                <button class="page-link" type="button" @click="goToPage(page)">
                                    {{ page }}
                                </button>
                            </li>

                            <li class="page-item"
                                :class="{ disabled: pagination.current_page === pagination.last_page }">
                                <button class="page-link" type="button" @click="goToPage(pagination.current_page + 1)"
                                    :disabled="pagination.current_page === pagination.last_page">
                                    Next
                                </button>
                            </li>

                            <li class="page-item"
                                :class="{ disabled: pagination.current_page === pagination.last_page }">
                                <button class="page-link" type="button" @click="goToPage(pagination.last_page)"
                                    :disabled="pagination.current_page === pagination.last_page">
                                    Last
                                </button>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Create / Edit Modal -->
        <div class="modal fade" id="healthFacilityModal" tabindex="-1" aria-hidden="true" ref="healthFacilityModalEl">
            <div class="modal-dialog modal-dialog-centered modal-xl custom-modal-xl">
                <div class="modal-content rounded-4 border-0 shadow modal-gradient">
                    <div class="modal-header border-0 pb-2">
                        <div>
                            <h5 class="modal-title fw-bold mb-1">
                                {{
                                    editMode
                                        ? t("healthFacility.editTitle")
                                        : t("healthFacility.createTitle")
                                }}
                            </h5>
                            <p class="text-muted small mb-0">
                                {{
                                    editMode
                                        ? t("healthFacility.editSubtitle")
                                        : t("healthFacility.createSubtitle")
                                }}
                            </p>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body pt-2 px-3 px-lg-4">
                        <form @submit.prevent="
                            editMode ? updateHealthFacility() : createHealthFacility()
                            ">
                            <div class="row g-4">
                                <!-- LEFT -->
                                <div class="col-lg-6">
                                    <div class="form-section h-100">
                                        <div class="section-title">
                                            <i class="fa-solid fa-hospital me-2"></i>
                                            {{ t("healthFacility.sectionInfo") }}
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    {{ t("healthFacility.codeLabel") }}
                                                </label>
                                                <input type="text" class="form-control custom-input"
                                                    v-model.trim="form.code" required />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    {{ t("healthFacility.typeLabel") }}
                                                </label>
                                                <select class="form-select custom-input" v-model="form.type"
                                                    @change="onTypeChange" required>
                                                    <option value="">
                                                        {{ t("healthFacility.selectTypeOption") }}
                                                    </option>
                                                    <option v-for="type in facilityTypes" :key="type.value"
                                                        :value="type.value">
                                                        {{ t(type.labelKey) }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    {{ t("healthFacility.nameEnLabel") }}
                                                </label>
                                                <input type="text" class="form-control custom-input"
                                                    v-model.trim="form.name_en" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    {{ t("healthFacility.nameKhLabel") }}
                                                </label>
                                                <input type="text" class="form-control custom-input"
                                                    v-model.trim="form.name_km" required />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    {{ t("healthFacility.parentLabel") }}
                                                </label>
                                                <select class="form-select custom-input" v-model="form.parent_id"
                                                    :disabled="!requiresParent">
                                                    <option value="">
                                                        {{ t("healthFacility.selectParentOption") }}
                                                    </option>
                                                    <option v-for="parent in filteredParents" :key="parent.id"
                                                        :value="parent.id">
                                                        {{ parent.code }} -
                                                        {{ parent.name_en || parent.name_km }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    {{ t("healthFacility.statusLabel") }}
                                                </label>
                                                <select class="form-select custom-input" v-model="form.is_active">
                                                    <option :value="true">
                                                        {{ t("healthFacility.active") }}
                                                    </option>
                                                    <option :value="false">
                                                        {{ t("healthFacility.inactive") }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    {{ t("healthFacility.provinceLabel") }}
                                                </label>
                                                <select class="form-select custom-input" v-model="form.province_code"
                                                    @change="onProvinceChange">
                                                    <option value="">
                                                        {{ t("healthFacility.selectProvinceOption") }}
                                                    </option>
                                                    <option v-for="province in provinces" :key="province.province_code"
                                                        :value="province.province_code">
                                                        {{
                                                            province.province_name_en ||
                                                            province.province_name_kh
                                                        }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    {{ t("healthFacility.districtLabel") }}
                                                </label>
                                                <select class="form-select custom-input" v-model="form.district_code"
                                                    @change="onDistrictChange" :disabled="!form.province_code">
                                                    <option value="">
                                                        {{ t("healthFacility.selectDistrictOption") }}
                                                    </option>
                                                    <option v-for="district in filteredDistricts"
                                                        :key="district.district_code" :value="district.district_code">
                                                        {{
                                                            district.district_name_en ||
                                                            district.district_name_kh
                                                        }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    {{ t("healthFacility.communeLabel") }}
                                                </label>
                                                <select class="form-select custom-input" v-model="form.commune_code"
                                                    @change="onCommuneChange" :disabled="!form.district_code">
                                                    <option value="">
                                                        {{ t("healthFacility.selectCommuneOption") }}
                                                    </option>
                                                    <option v-for="commune in filteredCommunes"
                                                        :key="commune.commune_code" :value="commune.commune_code">
                                                        {{
                                                            commune.commune_name_en ||
                                                            commune.commune_name_kh
                                                        }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    {{ t("healthFacility.villageLabel") }}
                                                </label>
                                                <select class="form-select custom-input" v-model="form.village_code"
                                                    :disabled="!form.commune_code">
                                                    <option value="">
                                                        {{ t("healthFacility.selectVillageOption") }}
                                                    </option>
                                                    <option v-for="village in filteredVillages"
                                                        :key="village.village_code" :value="village.village_code">
                                                        {{
                                                            village.village_name_en ||
                                                            village.village_name_kh
                                                        }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    Director Name
                                                </label>
                                                <input type="text" class="form-control custom-input"
                                                    v-model.trim="form.director_name" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    Contact Phone
                                                </label>
                                                <input type="text" class="form-control custom-input"
                                                    v-model.trim="form.contact_phone" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    {{ t("healthFacility.latitudeLabel") }}
                                                </label>
                                                <input type="number" step="any" class="form-control custom-input"
                                                    v-model="form.latitude" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    {{ t("healthFacility.longitudeLabel") }}
                                                </label>
                                                <input type="number" step="any" class="form-control custom-input"
                                                    v-model="form.longitude" />
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">
                                                    {{ t("healthFacility.imageLabel") }}
                                                </label>

                                                <input ref="imageInput" type="file" class="d-none" accept="image/*"
                                                    @change="onImageChange" />

                                                <div class="upload-box">
                                                    <div class="upload-preview">
                                                        <img v-if="currentPreviewImage" :src="currentPreviewImage"
                                                            alt="Preview" class="upload-preview-image"
                                                            @error="handleFormImageError" />
                                                        <div v-else class="upload-placeholder">
                                                            <div class="upload-placeholder-icon">
                                                                <i class="fa-solid fa-image"></i>
                                                            </div>
                                                            <div class="upload-placeholder-title">
                                                                {{ t("healthFacility.noImageSelected") }}
                                                            </div>
                                                            <div class="upload-placeholder-text">
                                                                {{ t("healthFacility.imageNote") }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="upload-actions">
                                                        <button type="button" class="btn btn-upload"
                                                            @click="triggerImageInput">
                                                            <i class="fa-solid fa-upload me-1"></i>
                                                            {{ t("healthFacility.uploadImage") }}
                                                        </button>

                                                        <button type="button" class="btn btn-preview"
                                                            @click="openPreviewImage" :disabled="!currentPreviewImage">
                                                            <i class="fa-solid fa-eye me-1"></i>
                                                            {{ t("healthFacility.previewImage") }}
                                                        </button>

                                                        <button type="button" class="btn btn-remove-upload"
                                                            @click="removeImage" :disabled="!currentPreviewImage">
                                                            <i class="fa-solid fa-trash me-1"></i>
                                                            {{ t("healthFacility.removeImage") }}
                                                        </button>
                                                    </div>

                                                    <div class="upload-file-name" v-if="imageName">
                                                        <i class="fa-solid fa-file-image me-1"></i>
                                                        {{ imageName }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT -->
                                <div class="col-lg-6">
                                    <div class="form-section map-section h-100">
                                        <div class="section-title">
                                            <i class="fa-solid fa-location-dot me-2"></i>
                                            {{ t("healthFacility.sectionMap") }}
                                        </div>

                                        <div id="village-map" class="map-box rounded-4 overflow-hidden"></div>

                                        <div class="map-note mt-3">
                                            <div class="col-12 d-flex flex-wrap gap-2">
                                                <button type="button" class="btn btn-outline-secondary rounded-4"
                                                    @click="getCurrentLocation">
                                                    <i class="fa-solid fa-location-crosshairs me-1"></i>
                                                    {{ t("healthFacility.getCurrentLocation") }}
                                                </button>

                                                <button type="button" class="btn btn-outline-primary rounded-4"
                                                    @click="syncMapFromInputs">
                                                    <i class="fa-solid fa-map-pin me-1"></i>
                                                    {{ t("healthFacility.updateMapPoint") }}
                                                </button>
                                            </div>

                                            <div class="map-note-item">
                                                <i class="fa-solid fa-circle-info me-2"></i>
                                                {{ t("healthFacility.mapClickNote") }}
                                            </div>
                                            <div class="map-note-item">
                                                <i class="fa-solid fa-arrows-up-down-left-right me-2"></i>
                                                {{ t("healthFacility.mapDragNote") }}
                                            </div>
                                        </div>

                                        <div class="map-summary mt-3">
                                            <div class="summary-chip">
                                                <span class="label">
                                                    {{ t("healthFacility.latitude") }}
                                                </span>
                                                <strong>{{ form.latitude || "-" }}</strong>
                                            </div>
                                            <div class="summary-chip">
                                                <span class="label">
                                                    {{ t("healthFacility.longitude") }}
                                                </span>
                                                <strong>{{ form.longitude || "-" }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer border-0 px-0 mt-4">
                                <button type="button" class="btn btn-light btn-action" data-bs-dismiss="modal">
                                    {{ t("healthFacility.cancel") }}
                                </button>

                                <button type="submit" class="btn btn-success btn-action">
                                    <i class="fa-solid fa-check me-1"></i>
                                    {{
                                        editMode
                                            ? t("healthFacility.update")
                                            : t("healthFacility.save")
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Preview Modal -->
        <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true" ref="imagePreviewModalEl">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">
                            {{ t("healthFacility.previewImage") }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <div class="preview-modal-wrap">
                            <img v-if="currentPreviewImage" :src="currentPreviewImage" alt="Preview"
                                class="preview-modal-image" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Modal -->
        <div class="modal fade" id="alertModal" tabindex="-1" aria-hidden="true" ref="alertModalEl">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-body p-4 text-center">
                        <div class="alert-icon mb-3" :class="alertState.iconClass">
                            <i class="fa-solid" :class="alertState.icon"></i>
                        </div>

                        <h5 class="mb-2">{{ alertState.title }}</h5>
                        <p class="text-muted mb-0">{{ alertState.message }}</p>

                        <div class="mt-4">
                            <button type="button" class="btn" :class="alertState.btnClass" data-bs-dismiss="modal">
                                {{ t("healthFacility.ok") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Delete Modal -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true" ref="confirmDeleteModalEl">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-body p-4 text-center">
                        <div class="mb-3 text-danger" style="font-size: 40px">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>

                        <h5 class="mb-2">{{ t("healthFacility.confirmDeleteTitle") }}</h5>
                        <p class="text-muted mb-0">
                            {{ t("healthFacility.confirmDeleteMessage") }}
                        </p>

                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                :disabled="deleting">
                                {{ t("healthFacility.cancel") }}
                            </button>

                            <button type="button" class="btn btn-danger" @click="confirmDelete" :disabled="deleting">
                                <span v-if="deleting" class="spinner-border spinner-border-sm me-2"></span>
                                {{ t("healthFacility.confirmDeleteButton") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="pageLoading || modalLoading || submitLoading" class="loading-overlay" role="dialog"
            aria-modal="true">
            <div class="loading-box">
                <div class="spinner"></div>
                <div class="loading-text">{{ t("healthFacility.loading") }}</div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios"
import { Modal } from "bootstrap"
import { useI18n } from "vue-i18n"

export default {
    name: "HealthFacilityIndex",

    setup() {
        const { t } = useI18n()
        return { t }
    },

    data() {
        return {
            healthFacilities: [],
            parents: [],
            provinces: [],
            districts: [],
            communes: [],
            villages: [],

            filteredDistricts: [],
            filteredCommunes: [],
            filteredVillages: [],

            facilityTypes: [
                { value: "PHD", labelKey: "healthFacility.types.PHD" },
                { value: "NH", labelKey: "healthFacility.types.NH" },
                { value: "PH", labelKey: "healthFacility.types.PH" },
                { value: "OD", labelKey: "healthFacility.types.OD" },
                { value: "RH", labelKey: "healthFacility.types.RH" },
                { value: "HC", labelKey: "healthFacility.types.HC" },
                { value: "HP", labelKey: "healthFacility.types.HP" },
            ],

            editMode: false,
            editId: null,
            deleting: false,
            deleteTarget: null,

            healthFacilityModal: null,
            imagePreviewModal: null,
            alertModal: null,
            confirmDeleteModal: null,

            pageLoading: false,
            modalLoading: false,
            submitLoading: false,

            map: null,
            marker: null,
            defaultCenter: {
                lat: 11.5564,
                lng: 104.9282,
            },

            pagination: {
                current_page: 1,
                last_page: 1,
                per_page: 10,
                total: 0,
                from: 0,
                to: 0,
            },

            form: {
                code: "",
                name_en: "",
                name_km: "",
                type: "",
                parent_id: "",
                province_code: "",
                district_code: "",
                commune_code: "",
                village_code: "",
                latitude: "",
                longitude: "",
                hf_image: "",
                director_name: "",
                contact_phone: "",
                is_active: true,
            },

            selectedImageFile: null,
            selectedImagePreview: "",
            existingImageUrl: "",
            imageName: "",
            removeImageFlag: false,

            alertState: {
                title: "",
                message: "",
                icon: "fa-circle-check",
                iconClass: "success",
                btnClass: "btn-success",
            },
        }
    },

    computed: {
        visiblePages() {
            const current = this.pagination.current_page
            const last = this.pagination.last_page
            const delta = 2

            let start = current - delta
            let end = current + delta

            if (start < 1) {
                start = 1
                end = Math.min(5, last)
            }

            if (end > last) {
                end = last
                start = Math.max(1, last - 4)
            }

            const pages = []
            for (let i = start; i <= end; i++) {
                pages.push(i)
            }
            return pages
        },

        requiredParentType() {
            const map = {
                PHD: null,
                NH: null,
                PH: "PHD",
                OD: "PHD",
                RH: "OD",
                HC: "OD",
                HP: "HC",
            }

            return map[this.form.type] || null
        },

        requiresParent() {
            return !!this.requiredParentType
        },

        filteredParents() {
            if (!this.requiredParentType) return []

            return this.parents.filter(
                (item) => String(item.type).trim().toUpperCase() === String(this.requiredParentType).trim().toUpperCase()
            )
        },

        currentPreviewImage() {
            return this.selectedImagePreview || this.existingImageUrl || ""
        },
    },

    watch: {
        pageLoading: "toggleBodyScroll",
        modalLoading: "toggleBodyScroll",
        submitLoading: "toggleBodyScroll",
    },

    async mounted() {
        this.pageLoading = true

        this.healthFacilityModal = new Modal(this.$refs.healthFacilityModalEl, {
            backdrop: "static",
            keyboard: false,
        })

        this.imagePreviewModal = new Modal(this.$refs.imagePreviewModalEl, {
            backdrop: true,
            keyboard: true,
        })

        this.alertModal = new Modal(this.$refs.alertModalEl, {
            backdrop: "static",
            keyboard: true,
        })

        this.confirmDeleteModal = new Modal(this.$refs.confirmDeleteModalEl, {
            backdrop: "static",
            keyboard: false,
        })

        try {
            await Promise.all([
                this.loadParents(),
                this.loadHealthFacilities(),
                this.loadProvinces(),
                this.loadDistricts(),
                this.loadCommunes(),
                this.loadVillages(),
            ])
        } finally {
            this.pageLoading = false
        }
    },

    beforeUnmount() {
        document.body.style.overflow = ""
    },

    methods: {
        rowNumber(index) {
            return (this.pagination.current_page - 1) * this.pagination.per_page + index + 1
        },

        normalizeImageUrl(url) {
            if (!url) return ""
            if (String(url).startsWith("http://") || String(url).startsWith("https://")) {
                return encodeURI(String(url))
            }
            return encodeURI(`/storage/${String(url).replace(/^storage\//, "")}`)
        },

        resolveImageUrl(item) {
            if (item?.hf_image_url) return this.normalizeImageUrl(item.hf_image_url)
            if (item?.hf_image) return this.normalizeImageUrl(item.hf_image)
            return ""
        },

        handleTableImageError(event) {
            event.target.style.display = "none"
        },

        handleFormImageError(event) {
            console.log("Preview image failed:", event?.target?.src || this.currentPreviewImage)
            this.showAlert(
                "warning",
                this.t("healthFacility.previewImage"),
                this.t("healthFacility.imagePreviewFailed")
            )
        },

        triggerImageInput() {
            this.$refs.imageInput?.click()
        },

        onImageChange(event) {
            const file = event.target.files?.[0]
            if (!file) return

            if (!file.type.startsWith("image/")) {
                this.showAlert(
                    "warning",
                    this.t("healthFacility.validationError"),
                    this.t("healthFacility.imageInvalidType")
                )
                return
            }

            this.selectedImageFile = file
            this.imageName = file.name
            this.removeImageFlag = false
            this.existingImageUrl = ""

            const reader = new FileReader()
            reader.onload = (e) => {
                this.selectedImagePreview = e.target?.result || ""
            }
            reader.readAsDataURL(file)
        },

        openPreviewImage() {
            if (!this.currentPreviewImage) return
            this.imagePreviewModal?.show()
        },

        removeImage() {
            this.selectedImageFile = null
            this.selectedImagePreview = ""
            this.existingImageUrl = ""
            this.imageName = ""

            if (this.form.hf_image) {
                this.removeImageFlag = true
                this.form.hf_image = ""
            }

            if (this.$refs.imageInput) {
                this.$refs.imageInput.value = ""
            }
        },

        toggleBodyScroll() {
            const hasLoading = this.pageLoading || this.modalLoading || this.submitLoading
            document.body.style.overflow = hasLoading ? "hidden" : ""
        },

        showAlert(type, title, message) {
            const map = {
                success: {
                    icon: "fa-circle-check",
                    iconClass: "success",
                    btnClass: "btn-success",
                },
                error: {
                    icon: "fa-triangle-exclamation",
                    iconClass: "error",
                    btnClass: "btn-warning",
                },
                fail: {
                    icon: "fa-circle-xmark",
                    iconClass: "fail",
                    btnClass: "btn-danger",
                },
                warning: {
                    icon: "fa-triangle-exclamation",
                    iconClass: "warning",
                    btnClass: "btn-warning",
                },
                info: {
                    icon: "fa-circle-info",
                    iconClass: "info",
                    btnClass: "btn-primary",
                },
            }

            const cfg = map[type] || map.info
            this.alertState.title = title
            this.alertState.message = message
            this.alertState.icon = cfg.icon
            this.alertState.iconClass = cfg.iconClass
            this.alertState.btnClass = cfg.btnClass
            this.alertModal?.show()
        },

        resetForm() {
            this.form = {
                code: "",
                name_en: "",
                name_km: "",
                type: "",
                parent_id: "",
                province_code: "",
                district_code: "",
                commune_code: "",
                village_code: "",
                latitude: "",
                longitude: "",
                hf_image: "",
                director_name: "",
                contact_phone: "",
                is_active: true,
            }

            this.selectedImageFile = null
            this.selectedImagePreview = ""
            this.existingImageUrl = ""
            this.imageName = ""
            this.removeImageFlag = false

            this.filteredDistricts = []
            this.filteredCommunes = []
            this.filteredVillages = []

            if (this.$refs.imageInput) {
                this.$refs.imageInput.value = ""
            }
        },

        buildFormData() {
            const formData = new FormData()

            formData.append("code", this.form.code || "")
            formData.append("name_en", this.form.name_en || "")
            formData.append("name_km", this.form.name_km || "")
            formData.append("type", this.form.type || "")
            formData.append(
                "parent_id",
                this.requiresParent ? this.form.parent_id || "" : ""
            )
            formData.append("province_code", this.form.province_code || "")
            formData.append("district_code", this.form.district_code || "")
            formData.append("commune_code", this.form.commune_code || "")
            formData.append("village_code", this.form.village_code || "")
            formData.append("latitude", this.form.latitude || "")
            formData.append("longitude", this.form.longitude || "")
            formData.append("director_name", this.form.director_name || "")
            formData.append("contact_phone", this.form.contact_phone || "")
            formData.append("is_active", this.form.is_active ? 1 : 0)

            if (this.selectedImageFile) {
                formData.append("hf_image", this.selectedImageFile)
            }

            if (this.removeImageFlag) {
                formData.append("remove_hf_image", "1")
            }

            return formData
        },

        async goToPage(page) {
            if (
                page < 1 ||
                page > this.pagination.last_page ||
                page === this.pagination.current_page
            ) {
                return
            }

            await this.loadHealthFacilities(page)
        },

        async changePerPage() {
            await this.loadHealthFacilities(1)
        },

        async loadParents() {
            try {
                const res = await axios.get("/health-facilities", {
                    params: { per_page: 1000 },
                })
                this.parents = res.data.data || []
            } catch (err) {
                console.log(err.response?.data || err.message)
            }
        },

        async loadHealthFacilities(page = this.pagination.current_page) {
            this.pageLoading = true
            try {
                const res = await axios.get("/health-facilities", {
                    params: {
                        page,
                        per_page: this.pagination.per_page,
                    },
                })

                this.healthFacilities = res.data.data || []

                const meta = res.data.meta || {}
                this.pagination.current_page = Number(meta.current_page || 1)
                this.pagination.last_page = Number(meta.last_page || 1)
                this.pagination.per_page = Number(meta.per_page || this.pagination.per_page)
                this.pagination.total = Number(meta.total || 0)
                this.pagination.from = Number(meta.from || 0)
                this.pagination.to = Number(meta.to || 0)
            } catch (err) {
                console.log(err.response?.data || err.message)
                this.showAlert(
                    "error",
                    this.t("healthFacility.loadFailed"),
                    this.t("healthFacility.loadHealthFacilityFailedMessage")
                )
            } finally {
                this.pageLoading = false
            }
        },

        async loadProvinces() {
            try {
                const res = await axios.get("/get-provinces")
                this.provinces = res.data.data || []
            } catch (err) {
                console.log(err.response?.data || err.message)
            }
        },

        async loadDistricts() {
            try {
                const res = await axios.get("/get-districts")
                this.districts = res.data.data || []
            } catch (err) {
                console.log(err.response?.data || err.message)
            }
        },

        async loadCommunes() {
            try {
                const res = await axios.get("/get-communes")
                this.communes = res.data.data || []
            } catch (err) {
                console.log(err.response?.data || err.message)
            }
        },

        async loadVillages() {
            try {
                const res = await axios.get("/get-villages", {
                    params: {
                        per_page: 5000,
                    },
                })
                this.villages = res.data.data || []
            } catch (err) {
                console.log(err.response?.data || err.message)
            }
        },

        onTypeChange() {
            if (!this.requiresParent) {
                this.form.parent_id = ""
                return
            }

            const exists = this.filteredParents.some(
                (parent) => String(parent.id) === String(this.form.parent_id)
            )

            if (!exists) {
                this.form.parent_id = ""
            }
        },

        onProvinceChange() {
            this.form.district_code = ""
            this.form.commune_code = ""
            this.form.village_code = ""

            this.filteredDistricts = this.districts.filter(
                (d) => String(d.province_code) === String(this.form.province_code)
            )

            this.filteredCommunes = []
            this.filteredVillages = []
        },

        onDistrictChange() {
            this.form.commune_code = ""
            this.form.village_code = ""

            this.filteredCommunes = this.communes.filter(
                (c) => String(c.district_code) === String(this.form.district_code)
            )

            this.filteredVillages = []
        },

        onCommuneChange() {
            this.form.village_code = ""

            this.filteredVillages = this.villages.filter(
                (v) => String(v.commune_code) === String(this.form.commune_code)
            )
        },

        getCurrentLocation() {
            if (!navigator.geolocation) {
                this.showAlert(
                    "error",
                    this.t("village.geolocationError"),
                    this.t("village.geolocationNotSupported")
                )
                return
            }

            this.modalLoading = true

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude
                    const lng = position.coords.longitude

                    this.form.latitude = lat.toFixed(6)
                    this.form.longitude = lng.toFixed(6)

                    if (this.map && this.marker) {
                        this.setMarkerPosition(lat, lng)
                        this.map.setZoom(15)
                    } else {
                        this.initMap(lat, lng)
                    }

                    this.modalLoading = false
                },
                (error) => {
                    this.modalLoading = false

                    let message = this.t("village.unableToGetLocation")
                    if (error.code === 1) message = this.t("village.permissionDenied")
                    if (error.code === 2) message = this.t("village.locationUnavailable")
                    if (error.code === 3) message = this.t("village.locationTimeout")

                    this.showAlert("error", this.t("village.locationError"), message)
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0,
                }
            )
        },

        loadGoogleMapsScript() {
            return new Promise((resolve, reject) => {
                if (window.google && window.google.maps) {
                    resolve()
                    return
                }

                const existingScript = document.getElementById("googleMapsScript")
                if (existingScript) {
                    if (existingScript.dataset.loaded === "true") {
                        resolve()
                        return
                    }

                    existingScript.addEventListener("load", () => resolve(), { once: true })
                    existingScript.addEventListener(
                        "error",
                        () => reject(new Error("Google Maps failed to load")),
                        { once: true }
                    )
                    return
                }

                const script = document.createElement("script")
                script.id = "googleMapsScript"
                script.src =
                    "https://maps.googleapis.com/maps/api/js?key=AIzaSyCs8T5do-m_YasDGHU0bcti_e15di4G8Hk"
                script.async = true
                script.defer = true
                script.onload = () => {
                    script.dataset.loaded = "true"
                    resolve()
                }
                script.onerror = () => reject(new Error("Google Maps script load error"))
                document.head.appendChild(script)
            })
        },

        initMap(lat = null, lng = null) {
            const mapEl = document.getElementById("village-map")
            if (!mapEl || !(window.google && window.google.maps)) return

            const hasLat = lat !== null && lat !== "" && !isNaN(Number(lat))
            const hasLng = lng !== null && lng !== "" && !isNaN(Number(lng))

            const center = {
                lat: hasLat ? Number(lat) : this.defaultCenter.lat,
                lng: hasLng ? Number(lng) : this.defaultCenter.lng,
            }

            this.map = new window.google.maps.Map(mapEl, {
                center,
                zoom: hasLat && hasLng ? 12 : 7,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
            })

            this.marker = new window.google.maps.Marker({
                position: center,
                map: this.map,
                draggable: true,
            })

            if (!hasLat || !hasLng) {
                this.form.latitude = center.lat.toFixed(6)
                this.form.longitude = center.lng.toFixed(6)
            }

            this.map.addListener("click", (event) => {
                const clickedLat = event.latLng.lat()
                const clickedLng = event.latLng.lng()
                this.setMarkerPosition(clickedLat, clickedLng)
            })

            this.marker.addListener("dragend", (event) => {
                const draggedLat = event.latLng.lat()
                const draggedLng = event.latLng.lng()
                this.setMarkerPosition(draggedLat, draggedLng)
            })

            setTimeout(() => {
                if (this.map && window.google?.maps?.event) {
                    window.google.maps.event.trigger(this.map, "resize")
                    this.map.setCenter(center)
                }
            }, 300)
        },

        setMarkerPosition(lat, lng) {
            if (!this.map || !this.marker) return

            const newPos = {
                lat: Number(lat),
                lng: Number(lng),
            }

            this.marker.setPosition(newPos)
            this.map.setCenter(newPos)
            this.form.latitude = Number(lat).toFixed(6)
            this.form.longitude = Number(lng).toFixed(6)
        },

        syncMapFromInputs() {
            const lat = Number(this.form.latitude)
            const lng = Number(this.form.longitude)

            if (isNaN(lat) || isNaN(lng)) {
                this.showAlert(
                    "warning",
                    this.t("healthFacility.validationError"),
                    this.t("healthFacility.invalidCoordinateMessage")
                )
                return
            }

            if (this.map && this.marker) {
                this.setMarkerPosition(lat, lng)
                this.map.setZoom(15)
            } else {
                this.initMap(lat, lng)
            }
        },

        async prepareMap(lat = null, lng = null) {
            try {
                await this.loadGoogleMapsScript()
                await this.$nextTick()

                setTimeout(() => {
                    this.initMap(lat, lng)
                }, 250)
            } catch (error) {
                console.log(error)
                this.showAlert(
                    "error",
                    this.t("healthFacility.mapLoadErrorTitle"),
                    this.t("healthFacility.mapLoadErrorMessage")
                )
            }
        },

        async openCreateModal() {
            this.modalLoading = true
            this.editMode = false
            this.editId = null
            this.resetForm()
            this.healthFacilityModal.show()

            await this.prepareMap()

            this.modalLoading = false
        },

        async openEditModal(item) {
            this.modalLoading = true
            this.editMode = true
            this.editId = item.id

            this.resetForm()

            try {
                const res = await axios.get(`/health-facilities/${item.id}`)
                const detail = res.data?.data || {}

                this.form.code = detail.code || ""
                this.form.name_en = detail.name_en || ""
                this.form.name_km = detail.name_km || ""
                this.form.type = detail.type || ""
                this.form.parent_id = detail.parent_id || ""
                this.form.province_code = detail.province_code || ""
                this.form.district_code = detail.district_code || ""
                this.form.commune_code = detail.commune_code || ""
                this.form.village_code = detail.village_code || ""
                this.form.latitude = detail.latitude || ""
                this.form.longitude = detail.longitude || ""
                this.form.hf_image = detail.hf_image || ""
                this.form.director_name = detail.director_name || ""
                this.form.contact_phone = detail.contact_phone || ""
                this.form.is_active = !!detail.is_active

                this.existingImageUrl = this.normalizeImageUrl(
                    detail.hf_image_url || detail.hf_image || ""
                )
                this.imageName = detail.hf_image
                    ? String(detail.hf_image).split("/").pop()
                    : ""

                this.filteredDistricts = this.districts.filter(
                    (d) => String(d.province_code) === String(this.form.province_code)
                )

                this.filteredCommunes = this.communes.filter(
                    (c) => String(c.district_code) === String(this.form.district_code)
                )

                this.filteredVillages = this.villages.filter(
                    (v) => String(v.commune_code) === String(this.form.commune_code)
                )

                this.healthFacilityModal.show()
                await this.prepareMap(this.form.latitude, this.form.longitude)
            } catch (err) {
                console.log(err.response?.data || err.message)
                this.showAlert(
                    "error",
                    this.t("healthFacility.loadFailed"),
                    this.t("healthFacility.loadHealthFacilityFailedMessage")
                )
            } finally {
                this.modalLoading = false
            }
        },

        async createHealthFacility() {
            this.submitLoading = true
            try {
                const formData = this.buildFormData()

                await axios.post("/health-facilities", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })

                await this.loadParents()
                await this.loadHealthFacilities(1)
                this.healthFacilityModal.hide()
                this.resetForm()

                this.showAlert(
                    "success",
                    this.t("healthFacility.success"),
                    this.t("healthFacility.createdSuccess")
                )
            } catch (err) {
                const errors = err.response?.data?.errors || {}
                const firstMsg =
                    Object.values(errors)?.[0]?.[0] ||
                    err.response?.data?.message ||
                    this.t("healthFacility.createFailedMessage")

                this.showAlert(
                    "error",
                    this.t("healthFacility.createFailed"),
                    firstMsg
                )
            } finally {
                this.submitLoading = false
            }
        },

        async updateHealthFacility() {
            if (!this.editId) return

            this.submitLoading = true
            try {
                const formData = this.buildFormData()
                formData.append("_method", "PUT")

                await axios.post(`/health-facilities/${this.editId}`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })

                await this.loadParents()
                await this.loadHealthFacilities(this.pagination.current_page)
                this.healthFacilityModal.hide()
                this.resetForm()

                this.showAlert(
                    "success",
                    this.t("healthFacility.success"),
                    this.t("healthFacility.updatedSuccess"),
                )
            } catch (err) {
                const errors = err.response?.data?.errors || {}
                const firstMsg =
                    Object.values(errors)?.[0]?.[0] ||
                    err.response?.data?.message ||
                    this.t("healthFacility.updateFailedMessage")

                this.showAlert(
                    "error",
                    this.t("healthFacility.updateFailed"),
                    firstMsg
                )
            } finally {
                this.submitLoading = false
            }
        },

        askDelete(item) {
            this.deleteTarget = item
            this.confirmDeleteModal?.show()
        },

        async confirmDelete() {
            if (!this.deleteTarget?.id) return

            this.deleting = true
            try {
                await axios.delete(`/health-facilities/${this.deleteTarget.id}`)

                const isLastItemOnPage = this.healthFacilities.length === 1
                const targetPage =
                    isLastItemOnPage && this.pagination.current_page > 1
                        ? this.pagination.current_page - 1
                        : this.pagination.current_page

                this.confirmDeleteModal?.hide()
                await this.loadParents()
                await this.loadHealthFacilities(targetPage)

                this.showAlert(
                    "success",
                    this.t("healthFacility.success"),
                    this.t("healthFacility.deletedSuccess")
                )
            } catch (err) {
                console.log(err.response?.data || err.message)
                this.showAlert(
                    "error",
                    this.t("healthFacility.deleteFailed"),
                    err.response?.data?.message ||
                    err.message ||
                    this.t("healthFacility.deleteFailedMessage")
                )
            } finally {
                this.deleting = false
                this.deleteTarget = null
            }
        },
    },
}
</script>

<style scoped>
.health-facility-page {
    font-family: "Khmer OS Battambang", sans-serif;
    padding-bottom: 1.5rem;
}

.listing-card {
    background: #ffffff;
    border: 1px solid #d7dde5;
    border-radius: 24px;
    box-shadow: 0 12px 30px rgba(23, 43, 77, 0.08);
    overflow: hidden;
}

.listing-card-body {
    padding: 1rem 1rem 0.75rem;
}

.listing-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.listing-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2f3a45;
    margin: 0;
}

.listing-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.page-size-select {
    width: 95px;
    min-height: 42px;
    border-radius: 12px;
    border: 1px solid #cfd6de;
    color: #344054;
    box-shadow: none;
}

.btn-create {
    background: linear-gradient(135deg, #198754, #157347);
    border: 0;
    color: #fff;
    border-radius: 12px;
    min-height: 42px;
    padding: 0.5rem 1rem;
    font-weight: 600;
}

.custom-table-wrap {
    overflow: hidden;
}

.custom-list-table {
    margin-bottom: 0;
    font-size: 14px;
    color: #263238;
}

.custom-list-table thead th {
    background: linear-gradient(to bottom, #f5efb7 0%, #fff7cd 55%, #fffbe7 100%);
    color: #7c7128;
    font-weight: 700;
    font-size: 14px;
    padding: 0.9rem 0.75rem;
    border-bottom: 1px solid #e7e1ac;
    border-top: none;
    white-space: nowrap;
}

.custom-list-table tbody td {
    padding: 0.85rem 0.75rem;
    border-bottom: 1px solid #e5e9ef;
    vertical-align: middle;
    color: #20262d;
    background: #fff;
}

.custom-list-table tbody tr:hover td {
    background: #f9fbfd;
}

.table-image-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
}

.table-thumb {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    object-fit: cover;
    border: 1px solid #e5e7eb;
    background: #fff;
}

.table-thumb-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    background: #f8fafc;
}

.table-action-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-edit,
.btn-delete {
    min-width: 78px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    padding: 0.42rem 0.8rem;
    line-height: 1.2;
    border: 0;
}

.btn-edit {
    background: #0d6efd;
    color: #fff;
}

.btn-delete {
    background: #dc3545;
    color: #fff;
}

.type-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 52px;
    padding: 0.28rem 0.65rem;
    border-radius: 999px;
    background: #eef4ff;
    color: #0d6efd;
    font-weight: 700;
    font-size: 12px;
}

.status-pill {
    display: inline-block;
    padding: 0.28rem 0.7rem;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.status-active {
    background: #e9f9ef;
    color: #198754;
}

.status-inactive {
    background: #fdecec;
    color: #dc3545;
}

.listing-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    padding: 1rem 0 0.2rem;
}

.listing-info {
    font-size: 14px;
    color: #5d6876;
}

.custom-pagination {
    gap: 6px;
}

.custom-pagination .page-item .page-link {
    border-radius: 8px;
    border: 1px solid #cfd6de;
    color: #4b5563;
    background: #fff;
    min-width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 12px;
    box-shadow: none;
    font-size: 14px;
}

.custom-pagination .page-item.active .page-link {
    background: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

.custom-pagination .page-item.disabled .page-link {
    background: #f4f6f8;
    color: #98a2b3;
    border-color: #d8dee6;
}

.custom-modal-xl {
    max-width: 1380px;
}

.modal-gradient {
    background: linear-gradient(180deg, #fffdf5 0%, #fffefc 100%);
}

.form-section {
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid #efe7b8;
    border-radius: 22px;
    padding: 20px;
    box-shadow: 0 10px 26px rgba(34, 48, 73, 0.06);
}

.section-title {
    font-size: 15px;
    font-weight: 700;
    color: #6c5d13;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 1px dashed #d8cb84;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 6px;
}

.custom-input {
    border-radius: 14px;
    min-height: 46px;
    border: 1px solid #dcdcdc;
    box-shadow: none;
    transition: all 0.2s ease;
}

.custom-input:focus {
    border-color: #d6c76d;
    box-shadow: 0 0 0 0.18rem rgba(214, 199, 109, 0.2);
}

.upload-box {
    border: 1px solid #e3e9f1;
    background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
    border-radius: 18px;
    padding: 14px;
}

.upload-preview {
    width: 100%;
    height: 260px;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    background: #f8fafc;
    margin-bottom: 12px;
}

.upload-preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.upload-placeholder {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    padding: 18px;
    background: linear-gradient(135deg, #f8fafc, #eef4ff);
}

.upload-placeholder-icon {
    width: 70px;
    height: 70px;
    border-radius: 999px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(13, 110, 253, 0.08);
    color: #0d6efd;
    font-size: 28px;
    margin-bottom: 10px;
}

.upload-placeholder-title {
    font-size: 14px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 4px;
}

.upload-placeholder-text {
    font-size: 12px;
    color: #64748b;
    max-width: 320px;
}

.upload-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.btn-upload,
.btn-preview,
.btn-remove-upload {
    min-height: 42px;
    border-radius: 12px;
    padding: 0.55rem 0.95rem;
    font-weight: 700;
    border: 0;
    font-size: 14px;
}

.btn-upload {
    background: linear-gradient(135deg, #0d6efd, #3b82f6);
    color: #fff;
}

.btn-preview {
    background: linear-gradient(135deg, #6f42c1, #8b5cf6);
    color: #fff;
}

.btn-remove-upload {
    background: linear-gradient(135deg, #dc3545, #ef4444);
    color: #fff;
}

.btn-preview:disabled,
.btn-remove-upload:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.upload-file-name {
    margin-top: 10px;
    background: #f8fafc;
    border: 1px solid #e8eef4;
    border-radius: 12px;
    padding: 10px 12px;
    font-size: 13px;
    color: #475569;
    word-break: break-word;
}

.preview-modal-wrap {
    width: 100%;
    max-height: 70vh;
    overflow: auto;
    border-radius: 16px;
    background: #f8fafc;
}

.preview-modal-image {
    width: 100%;
    display: block;
    object-fit: contain;
}

.map-section {
    display: flex;
    flex-direction: column;
}

.map-box {
    width: 100%;
    min-height: 520px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 18px;
}

.map-note {
    display: grid;
    gap: 8px;
}

.map-note-item {
    font-size: 13px;
    color: #6b7280;
    background: #f8fafc;
    border: 1px solid #e8eef4;
    border-radius: 12px;
    padding: 10px 12px;
}

.map-summary {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.summary-chip {
    min-width: 180px;
    background: #fff;
    border: 1px solid #e6ebf1;
    border-radius: 14px;
    padding: 10px 14px;
}

.summary-chip .label {
    display: block;
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 4px;
}

.alert-icon i {
    font-size: 60px;
}

.alert-icon.success i {
    color: #198754;
}

.alert-icon.error i {
    color: #f2b604;
}

.alert-icon.fail i {
    color: #dc3545;
}

.alert-icon.warning i {
    color: #ffc107;
}

.alert-icon.info i {
    color: #0d6efd;
}

.btn-action {
    min-width: 110px;
    border-radius: 12px;
    font-weight: 600;
    padding: 10px 18px;
    border: 1px solid gainsboro;
}

.loading-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
}

.loading-box {
    background: #fff;
    padding: 22px 26px;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    min-width: 240px;
    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
}

.spinner {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    border: 5px solid #e6e6e6;
    border-top-color: #eabe0d;
    animation: spin 0.9s linear infinite;
}

.loading-text {
    font-weight: 600;
    font-size: 14px;
    color: #333;
    text-align: center;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

@media (max-width: 991.98px) {
    .listing-title {
        font-size: 1.45rem;
    }

    .listing-toolbar {
        align-items: stretch;
    }

    .listing-actions {
        width: 100%;
        justify-content: flex-end;
    }

    .map-box {
        min-height: 380px;
    }

    .upload-preview {
        height: 220px;
    }
}

@media (max-width: 767.98px) {
    .listing-card-body {
        padding: 0.85rem 0.85rem 0.6rem;
    }

    .listing-title {
        font-size: 1.2rem;
    }

    .listing-actions {
        width: 100%;
        justify-content: space-between;
    }

    .page-size-select {
        width: 80px;
    }

    .btn-create {
        flex: 1;
        justify-content: center;
    }

    .listing-footer {
        flex-direction: column;
        align-items: flex-start;
    }

    .table-action-group {
        flex-direction: column;
        align-items: stretch;
    }

    .btn-edit,
    .btn-delete {
        width: 100%;
    }

    .map-box {
        min-height: 320px;
    }

    .upload-preview {
        height: 200px;
    }

    .upload-actions {
        flex-direction: column;
    }

    .btn-upload,
    .btn-preview,
    .btn-remove-upload {
        width: 100%;
    }
}
</style>
