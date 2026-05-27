<template>
    <div class="container-fluid district-page mt-4">
        <div class="listing-card">
            <div class="listing-card-body">
                <div class="listing-toolbar">
                    <h3 class="listing-title mb-0">
                        {{ t("village.title") }}
                    </h3>

                    <div class="listing-actions">
                        <select
                            class="form-select page-size-select"
                            v-model.number="pagination.per_page"
                            @change="changePerPage"
                        >
                            <option :value="10">10</option>
                            <option :value="25">25</option>
                            <option :value="50">50</option>
                            <option :value="100">100</option>
                        </select>

                        <button class="btn btn-create" @click="openCreateModal">
                            <i class="fa-solid fa-circle-plus me-1"></i>
                            {{ t("village.createButton") }}
                        </button>
                    </div>
                </div>

                <div class="table-responsive custom-table-wrap">
                    <table class="table custom-list-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="70">{{ t("village.tableId") }}</th>
                                <th>{{ t("village.province") }}</th>
                                <th>{{ t("village.district") }}</th>
                                <th>{{ t("village.commune") }}</th>
                                <th>{{ t("village.villageNameEn") }}</th>
                                <th>{{ t("village.villageNameKh") }}</th>
                                <th>{{ t("village.villageCode") }}</th>
                                <th>{{ t("village.latitude") }}</th>
                                <th>{{ t("village.longitude") }}</th>
                                <th width="200" class="text-end">{{ t("village.action") }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(v, index) in villages" :key="v.id || index">
                                <td>{{ rowNumber(index) }}</td>
                                <td>{{ v.province?.province_name_en || "-" }}</td>
                                <td>{{ v.district?.district_name_en || "-" }}</td>
                                <td>{{ v.commune?.commune_name_en || "-" }}</td>
                                <td>{{ v.village_name_en }}</td>
                                <td>{{ v.village_name_kh }}</td>
                                <td>{{ v.village_code }}</td>
                                <td>{{ v.latitude || "-" }}</td>
                                <td>{{ v.longitude || "-" }}</td>
                                <td class="text-end">
                                    <div class="table-action-group justify-content-end">
                                        <button class="btn btn-sm btn-edit" @click="openEditModal(v)">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>
                                            {{ t("village.edit") }}
                                        </button>

                                        <button class="btn btn-sm btn-delete" @click="askDelete(v)">
                                            <i class="fa-solid fa-trash me-1"></i>
                                            {{ t("village.delete") }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="villages.length === 0">
                                <td colspan="12" class="text-center text-muted py-4">
                                    {{ t("village.noData") }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="pagination.total > 0" class="listing-footer">
                    <div class="listing-info">
                        Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} entries
                    </div>

                    <nav>
                        <ul class="pagination custom-pagination mb-0">
                            <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                                <button
                                    class="page-link"
                                    type="button"
                                    @click="goToPage(1)"
                                    :disabled="pagination.current_page === 1"
                                >
                                    First
                                </button>
                            </li>

                            <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                                <button
                                    class="page-link"
                                    type="button"
                                    @click="goToPage(pagination.current_page - 1)"
                                    :disabled="pagination.current_page === 1"
                                >
                                    Prev
                                </button>
                            </li>

                            <li
                                v-for="page in visiblePages"
                                :key="page"
                                class="page-item"
                                :class="{ active: page === pagination.current_page }"
                            >
                                <button class="page-link" type="button" @click="goToPage(page)">
                                    {{ page }}
                                </button>
                            </li>

                            <li
                                class="page-item"
                                :class="{ disabled: pagination.current_page === pagination.last_page }"
                            >
                                <button
                                    class="page-link"
                                    type="button"
                                    @click="goToPage(pagination.current_page + 1)"
                                    :disabled="pagination.current_page === pagination.last_page"
                                >
                                    Next
                                </button>
                            </li>

                            <li
                                class="page-item"
                                :class="{ disabled: pagination.current_page === pagination.last_page }"
                            >
                                <button
                                    class="page-link"
                                    type="button"
                                    @click="goToPage(pagination.last_page)"
                                    :disabled="pagination.current_page === pagination.last_page"
                                >
                                    Last
                                </button>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Create / Edit Modal -->
        <div class="modal fade" id="villageModal" tabindex="-1" aria-hidden="true" ref="villageModalEl">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow modal-gradient">
                    <div class="modal-header border-0 flex-column text-center position-relative pb-2">
                        <button
                            type="button"
                            class="btn-close position-absolute end-0 top-0 m-3"
                            data-bs-dismiss="modal"
                        ></button>

                        <div v-if="!editMode" class="w-100">
                            <img src="@/assets/logo_moh.png" alt="Logo MOH" class="logo mt-3" />
                            <h5 class="modal-title title-border pb-2">{{ t("village.createTitle") }}</h5>
                            <p class="text-muted small mb-0 mt-2">{{ t("village.createSubtitle") }}</p>
                        </div>

                        <div v-else class="w-100">
                            <h5 class="modal-title title-border pb-2 mt-3">{{ t("village.editTitle") }}</h5>
                            <p class="text-muted small mb-0 mt-2">{{ t("village.editSubtitle") }}</p>
                        </div>
                    </div>

                    <div class="modal-body pt-3 px-4 px-md-5">
                        <form @submit.prevent="editMode ? updateVillage() : createVillage()">
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="fa-solid fa-house me-2"></i>
                                    {{ t("village.sectionInfo") }}
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">{{ t("village.selectProvince") }}</label>
                                        <select
                                            class="form-select custom-input"
                                            v-model="form.province_code"
                                            @change="onProvinceChange"
                                            required
                                        >
                                            <option value="">{{ t("village.selectProvinceOption") }}</option>
                                            <option v-for="p in provinces" :key="p.id" :value="p.province_code">
                                                {{ p.province_name_en }} - {{ p.province_name_kh }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ t("village.selectDistrict") }}</label>
                                        <select
                                            class="form-select custom-input"
                                            v-model="form.district_code"
                                            @change="onDistrictChange"
                                            required
                                        >
                                            <option value="">{{ t("village.selectDistrictOption") }}</option>
                                            <option v-for="d in filteredDistricts" :key="d.id" :value="d.district_code">
                                                {{ d.district_name_en }} - {{ d.district_name_kh }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ t("village.selectCommune") }}</label>
                                        <select
                                            class="form-select custom-input"
                                            v-model="form.commune_code"
                                            @change="onCommuneChange"
                                            required
                                        >
                                            <option value="">{{ t("village.selectCommuneOption") }}</option>
                                            <option v-for="c in filteredCommunes" :key="c.id" :value="c.commune_code">
                                                {{ c.commune_name_en }} - {{ c.commune_name_kh }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ t("village.villageCodeLabel") }}</label>
                                        <input
                                            type="text"
                                            class="form-control custom-input bg-body-secondary"
                                            v-model="form.village_code"
                                            readonly
                                        />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ t("village.villageNameEnLabel") }}</label>
                                        <input
                                            type="text"
                                            class="form-control custom-input"
                                            v-model.trim="form.village_name_en"
                                            :placeholder="t('village.villageNameEnPlaceholder')"
                                            required
                                        />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ t("village.villageNameKhLabel") }}</label>
                                        <input
                                            type="text"
                                            class="form-control custom-input"
                                            v-model.trim="form.village_name_kh"
                                            :placeholder="t('village.villageNameKhPlaceholder')"
                                            required
                                        />
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">{{ t("village.latitudeLabel") }}</label>
                                        <input
                                            type="number"
                                            step="any"
                                            class="form-control custom-input"
                                            v-model="form.latitude"
                                            @input="updateMarkerFromInputs"
                                        />
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">{{ t("village.longitudeLabel") }}</label>
                                        <input
                                            type="number"
                                            step="any"
                                            class="form-control custom-input"
                                            v-model="form.longitude"
                                            @input="updateMarkerFromInputs"
                                        />
                                    </div>

                                    <div class="col-md-12">
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary rounded-4"
                                            @click="getCurrentLocation"
                                        >
                                            <i class="fa-solid fa-location-crosshairs me-1"></i>
                                            {{ t("village.getCurrentLocation") }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section mt-4">
                                <div class="section-title">
                                    <i class="fa-solid fa-location-dot me-2"></i>
                                    {{ t("village.sectionMap") }}
                                </div>

                                <div id="village-map" class="map-box rounded-4 shadow-sm"></div>
                                <small class="text-muted d-block mt-2">
                                    {{ t("village.mapNote") }}
                                </small>
                            </div>

                            <div class="modal-footer border-0 px-0 mt-4">
                                <button type="button" class="btn btn-light btn-action" data-bs-dismiss="modal">
                                    {{ t("village.cancel") }}
                                </button>

                                <button v-if="!editMode" type="submit" class="btn btn-success btn-action">
                                    <i class="fa-solid fa-check me-1"></i>
                                    {{ t("village.save") }}
                                </button>

                                <button v-else type="submit" class="btn btn-primary btn-action">
                                    <i class="fa-solid fa-check me-1"></i>
                                    {{ t("village.update") }}
                                </button>
                            </div>
                        </form>
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
                                {{ t("village.ok") }}
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

                        <h5 class="mb-2">{{ t("village.confirmDeleteTitle") }}</h5>
                        <p class="text-muted mb-0">{{ t("village.confirmDeleteMessage") }}</p>

                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal"
                                :disabled="deleting"
                            >
                                {{ t("village.cancel") }}
                            </button>

                            <button
                                type="button"
                                class="btn btn-danger"
                                @click="confirmDelete"
                                :disabled="deleting"
                            >
                                <span v-if="deleting" class="spinner-border spinner-border-sm me-2"></span>
                                {{ t("village.confirmDeleteButton") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="pageLoading || modalLoading || submitLoading"
            class="loading-overlay"
            role="dialog"
            aria-modal="true"
        >
            <div class="loading-box">
                <div class="spinner"></div>
                <div class="loading-text">{{ t("village.loading") }}</div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios"
import { Modal } from "bootstrap"
import { useI18n } from "vue-i18n"

export default {
    name: "VillageIndex",

    setup() {
        const { t } = useI18n()
        return { t }
    },

    data() {
        return {
            villages: [],
            provinces: [],
            districts: [],
            communes: [],

            filteredDistricts: [],
            filteredCommunes: [],

            editMode: false,
            editId: null,
            deleting: false,
            deleteTarget: null,

            villageModal: null,
            alertModal: null,
            confirmDeleteModal: null,

            map: null,
            marker: null,
            defaultCenter: { lat: 11.5564, lng: 104.9282 },

            pageLoading: false,
            modalLoading: false,
            submitLoading: false,

            pagination: {
                current_page: 1,
                last_page: 1,
                per_page: 10,
                total: 0,
                from: 0,
                to: 0,
            },

            form: {
                province_code: "",
                district_code: "",
                commune_code: "",
                village_name_en: "",
                village_name_kh: "",
                village_code: "",
                latitude: "",
                longitude: "",
            },

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
    },

    async mounted() {
        this.pageLoading = true

        this.villageModal = new Modal(this.$refs.villageModalEl, {
            backdrop: "static",
            keyboard: false,
        })

        this.alertModal = new Modal(this.$refs.alertModalEl, {
            backdrop: "static",
            keyboard: true,
        })

        this.confirmDeleteModal = new Modal(this.$refs.confirmDeleteModalEl, {
            backdrop: "static",
            keyboard: false,
        })

        await this.loadProvinces()
        await this.loadDistricts()
        await this.loadCommunes()
        await this.loadVillages()

        try {
            await this.loadGoogleMapsScript()
        } catch (err) {
            this.showAlert("error", this.t("village.mapError"), this.t("village.mapErrorMessage"))
        } finally {
            this.pageLoading = false
        }
    },

    beforeUnmount() {
        document.body.style.overflow = ""
    },

    watch: {
        pageLoading: "toggleBodyScroll",
        modalLoading: "toggleBodyScroll",
        submitLoading: "toggleBodyScroll",
    },

    methods: {
        rowNumber(index) {
            return (this.pagination.current_page - 1) * this.pagination.per_page + index + 1
        },

        async goToPage(page) {
            if (
                page < 1 ||
                page > this.pagination.last_page ||
                page === this.pagination.current_page
            ) {
                return
            }

            await this.loadVillages(page)
        },

        async changePerPage() {
            await this.loadVillages(1)
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
                province_code: "",
                district_code: "",
                commune_code: "",
                village_name_en: "",
                village_name_kh: "",
                village_code: "",
                latitude: "",
                longitude: "",
            }

            this.filteredDistricts = []
            this.filteredCommunes = []
        },

        async loadProvinces() {
            try {
                const res = await axios.get("/get-provinces")
                this.provinces = res.data.data || []
            } catch (err) {
                console.log(err.response?.data || err.message)
                this.showAlert("error", this.t("village.loadFailed"), this.t("village.loadProvinceFailedMessage"))
            }
        },

        async loadDistricts() {
            try {
                const res = await axios.get("/get-districts")
                this.districts = res.data.data || []
            } catch (err) {
                console.log(err.response?.data || err.message)
                this.showAlert("error", this.t("village.loadFailed"), this.t("village.loadDistrictFailedMessage"))
            }
        },

        async loadCommunes() {
            try {
                const res = await axios.get("/get-communes")
                this.communes = res.data.data || []
            } catch (err) {
                console.log(err.response?.data || err.message)
                this.showAlert("error", this.t("village.loadFailed"), this.t("village.loadCommuneFailedMessage"))
            }
        },

        async loadVillages(page = this.pagination.current_page) {
            this.pageLoading = true

            try {
                const res = await axios.get("/villages", {
                    params: {
                        page,
                        per_page: this.pagination.per_page,
                    },
                })

                this.villages = res.data.data || []

                const meta = res.data.meta || {}
                this.pagination.current_page = Number(meta.current_page || 1)
                this.pagination.last_page = Number(meta.last_page || 1)
                this.pagination.per_page = Number(meta.per_page || this.pagination.per_page)
                this.pagination.total = Number(meta.total || 0)
                this.pagination.from = Number(meta.from || 0)
                this.pagination.to = Number(meta.to || 0)
            } catch (err) {
                console.log(err.response?.data || err.message)
                this.showAlert("error", this.t("village.loadFailed"), this.t("village.loadVillageFailedMessage"))
            } finally {
                this.pageLoading = false
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
        },

        onDistrictChange() {
            this.form.commune_code = ""
            this.form.village_code = ""

            this.filteredCommunes = this.communes.filter(
                (c) => String(c.district_code) === String(this.form.district_code)
            )
        },

        async onCommuneChange() {
            this.form.village_code = ""

            if (!this.form.commune_code) {
                return
            }

            try {
                const res = await axios.get(`/villages/next-code/${this.form.commune_code}`)
                this.form.village_code = res.data.village_code || ""
            } catch (err) {
                console.log(err.response?.data || err.message)
                this.showAlert("error", this.t("village.codeError"), this.t("village.codeErrorMessage"))
            }
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
                script.onload = () => resolve()
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
            const position = {
                lat: Number(lat),
                lng: Number(lng),
            }

            this.form.latitude = position.lat.toFixed(6)
            this.form.longitude = position.lng.toFixed(6)

            if (this.marker) {
                this.marker.setPosition(position)
            }

            if (this.map) {
                this.map.panTo(position)
            }
        },

        updateMarkerFromInputs() {
            const lat = parseFloat(this.form.latitude)
            const lng = parseFloat(this.form.longitude)

            if (isNaN(lat) || isNaN(lng) || !this.map || !this.marker) return

            const position = { lat, lng }
            this.marker.setPosition(position)
            this.map.panTo(position)
        },

        openCreateModal() {
            this.modalLoading = true
            this.editMode = false
            this.editId = null
            this.resetForm()
            this.villageModal.show()

            setTimeout(() => {
                this.initMap()
                this.modalLoading = false
            }, 500)
        },

        openEditModal(village) {
            this.modalLoading = true
            this.editMode = true
            this.editId = village.id

            this.form.province_code = village.province_code || ""

            this.filteredDistricts = this.districts.filter(
                (d) => String(d.province_code) === String(this.form.province_code)
            )

            this.form.district_code = village.district_code || ""

            this.filteredCommunes = this.communes.filter(
                (c) => String(c.district_code) === String(this.form.district_code)
            )

            this.form.commune_code = village.commune_code || ""
            this.form.village_name_en = village.village_name_en || ""
            this.form.village_name_kh = village.village_name_kh || ""
            this.form.village_code = village.village_code || ""
            this.form.latitude = village.latitude || ""
            this.form.longitude = village.longitude || ""

            this.villageModal.show()

            setTimeout(() => {
                this.initMap(this.form.latitude, this.form.longitude)
                this.modalLoading = false
            }, 500)
        },

        async createVillage() {
            if (
                !this.form.province_code ||
                !this.form.district_code ||
                !this.form.commune_code ||
                !this.form.village_name_en ||
                !this.form.village_name_kh ||
                !this.form.village_code
            ) {
                this.showAlert(
                    "warning",
                    this.t("village.validationError"),
                    this.t("village.fillRequired")
                )
                return
            }

            this.submitLoading = true
            try {
                await axios.post("/villages", {
                    province_code: this.form.province_code,
                    district_code: this.form.district_code,
                    commune_code: this.form.commune_code,
                    village_name_en: this.form.village_name_en,
                    village_name_kh: this.form.village_name_kh,
                    village_code: this.form.village_code,
                    latitude: this.form.latitude,
                    longitude: this.form.longitude,
                })

                await this.loadVillages(1)
                this.villageModal.hide()
                this.resetForm()
                this.showAlert("success", this.t("village.success"), this.t("village.createdSuccess"))
            } catch (err) {
                if (err.response?.status === 422) {
                    const errors = err.response.data.errors || {}
                    const firstMsg =
                        Object.values(errors)?.[0]?.[0] ||
                        err.response?.data?.message ||
                        this.t("village.validationMessage")

                    this.showAlert("warning", this.t("village.validationError"), firstMsg)
                    return
                }

                console.log(err.response?.data || err.message)
                this.showAlert(
                    "error",
                    this.t("village.createFailed"),
                    err.response?.data?.message || err.message || this.t("village.createFailedMessage")
                )
            } finally {
                this.submitLoading = false
            }
        },

        async updateVillage() {
            if (!this.editId) return

            if (
                !this.form.province_code ||
                !this.form.district_code ||
                !this.form.commune_code ||
                !this.form.village_name_en ||
                !this.form.village_name_kh ||
                !this.form.village_code
            ) {
                this.showAlert(
                    "warning",
                    this.t("village.validationError"),
                    this.t("village.fillRequired")
                )
                return
            }

            this.submitLoading = true
            try {
                await axios.put(`/villages/${this.editId}`, {
                    province_code: this.form.province_code,
                    district_code: this.form.district_code,
                    commune_code: this.form.commune_code,
                    village_name_en: this.form.village_name_en,
                    village_name_kh: this.form.village_name_kh,
                    village_code: this.form.village_code,
                    latitude: this.form.latitude,
                    longitude: this.form.longitude,
                })

                await this.loadVillages(this.pagination.current_page)
                this.villageModal.hide()
                this.resetForm()
                this.showAlert("success", this.t("village.success"), this.t("village.updatedSuccess"))
            } catch (err) {
                if (err.response?.status === 422) {
                    const errors = err.response.data.errors || {}
                    const firstMsg =
                        Object.values(errors)?.[0]?.[0] ||
                        err.response?.data?.message ||
                        this.t("village.validationMessage")

                    this.showAlert("warning", this.t("village.validationError"), firstMsg)
                    return
                }

                console.log(err.response?.data || err.message)
                this.showAlert(
                    "error",
                    this.t("village.updateFailed"),
                    err.response?.data?.message || err.message || this.t("village.updateFailedMessage")
                )
            } finally {
                this.submitLoading = false
            }
        },

        askDelete(village) {
            this.deleteTarget = village
            this.confirmDeleteModal?.show()
        },

        async confirmDelete() {
            if (!this.deleteTarget?.id) return

            this.deleting = true
            try {
                await axios.delete(`/villages/${this.deleteTarget.id}`)

                const isLastItemOnPage = this.villages.length === 1
                const targetPage =
                    isLastItemOnPage && this.pagination.current_page > 1
                        ? this.pagination.current_page - 1
                        : this.pagination.current_page

                this.confirmDeleteModal?.hide()
                await this.loadVillages(targetPage)

                this.showAlert(
                    "success",
                    this.t("village.deletedTitle"),
                    this.t("village.deletedSuccess")
                )
            } catch (err) {
                console.log(err.response?.data || err.message)
                this.showAlert(
                    "error",
                    this.t("village.deleteFailed"),
                    err.response?.data?.message || err.message || this.t("village.deleteFailedMessage")
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
.district-page {
    font-family: "Khmer OS Battambang", sans-serif;
    padding-bottom: 1.5rem;
}

.listing-card {
    background: #ffffff;
    border: 1px solid #d7dde5;
    border-radius: 24px;
    box-shadow: 0 6px 18px rgba(23, 43, 77, 0.08);
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
    letter-spacing: 0;
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
    min-height: 40px;
    border-radius: 10px;
    border: 1px solid #cfd6de;
    color: #344054;
    box-shadow: none;
}

.page-size-select:focus {
    border-color: #9ea9b8;
    box-shadow: none;
}

.btn-create {
    background: #198754;
    border-color: #198754;
    color: #fff;
    border-radius: 10px;
    min-height: 40px;
    padding: 0.5rem 1rem;
    font-weight: 600;
    box-shadow: none;
}

.btn-create:hover {
    background: #157347;
    border-color: #157347;
    color: #fff;
}

.custom-table-wrap {
    border-radius: 0;
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

.table-action-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-edit,
.btn-delete {
    min-width: 70px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    padding: 0.38rem 0.7rem;
    line-height: 1.2;
    border: 0;
    box-shadow: none;
}

.btn-edit {
    background: #0d6efd;
    color: #fff;
}

.btn-edit:hover {
    background: #0b5ed7;
    color: #fff;
}

.btn-delete {
    background: #dc3545;
    color: #fff;
}

.btn-delete:hover {
    background: #bb2d3b;
    color: #fff;
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
    border-radius: 6px;
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

.modal-gradient {
    background: linear-gradient(180deg, #fffdf1 0%, #fffef9 100%);
}

.logo {
    width: 64px;
    margin: 0 auto 14px auto;
    display: block;
}

.title-border {
    display: inline-block;
    border-bottom: 2px solid #d6c76d;
    font-weight: 700;
}

.form-section {
    background: #ffffffcc;
    border: 1px solid #efe7b8;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
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
    border-radius: 12px;
    min-height: 46px;
    border: 1px solid #dcdcdc;
    box-shadow: none;
    transition: all 0.2s ease;
}

.custom-input:focus {
    border-color: #d6c76d;
    box-shadow: 0 0 0 0.18rem rgba(214, 199, 109, 0.2);
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

.map-box {
    width: 100%;
    height: 360px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
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
    background: rgba(0, 0, 0, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
}

.loading-box {
    background: #fff;
    padding: 22px 26px;
    border-radius: 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    min-width: 240px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
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
        font-size: 1.5rem;
    }

    .listing-toolbar {
        align-items: stretch;
    }

    .listing-actions {
        width: 100%;
        justify-content: flex-end;
    }
}

@media (max-width: 767.98px) {
    .listing-card-body {
        padding: 0.85rem 0.85rem 0.6rem;
    }

    .listing-title {
        font-size: 1.25rem;
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
}
</style>
