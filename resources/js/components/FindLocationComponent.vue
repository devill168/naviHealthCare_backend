<template>
  <div class="container-fluid find-location-page py-4">
    <div class="row justify-content-center">
      <div class="col-12 col-xl-11">
        <div class="main-shell card border-0 shadow-lg overflow-hidden">
          <!-- Header -->
          <div class="main-header">
            <div class="row align-items-center g-3">
              <div class="col-lg-8">
                <div class="d-flex align-items-center header-brand">
                  <div class="brand-logo-wrap">
                    <img src="@/assets/logo_moh.png" alt="Logo MOH" class="brand-logo" />
                  </div>

                  <div class="ms-3">
                    <div class="header-chip mb-2">
                      <i class="fa-solid fa-location-crosshairs me-2"></i>
                      {{ t("find_location.title") }}
                    </div>
                    <h2 class="header-title mb-1">
                      {{ t("find_location.title") }}
                    </h2>
                    <p class="header-subtitle mb-0">
                      {{ t("find_location.subtitle") }}
                    </p>
                  </div>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="text-lg-end d-flex justify-content-lg-end justify-content-start">
                  <button class="btn btn-reset-modern" @click="resetFilters">
                    <i class="fa-solid fa-rotate-right me-2"></i>
                    {{ t("find_location.reset") }}
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Body -->
          <div class="card-body p-3 p-md-4 p-xl-5">
            <div class="row g-4">
              <!-- Filter -->
              <div class="col-lg-8">
                <div class="content-card h-100">
                  <div class="card-section-header">
                    <div>
                      <h5 class="section-title mb-1">
                        <i class="fa-solid fa-sliders me-2"></i>
                        {{ t("find_location.title") }}
                      </h5>
                      <p class="section-subtitle mb-0">
                        {{ t("find_location.pleaseSelectLocationFilter") }}
                      </p>
                    </div>

                    <span class="badge badge-soft">
                      {{ t("find_location.databaseStyle") }}
                    </span>
                  </div>

                  <div class="row g-3">
                    <!-- Province -->
                    <div class="col-md-6">
                      <label class="form-label custom-label">
                        {{ t("find_location.province") }}
                      </label>
                      <div class="input-icon-wrap">
                        <i class="fa-solid fa-map input-icon"></i>
                        <select
                          v-model="form.province_code"
                          class="form-select custom-select"
                          @change="onProvinceChange"
                        >
                          <option value="">{{ t("find_location.selectProvince") }}</option>
                          <option
                            v-for="province in provinces"
                            :key="province.id"
                            :value="province.province_code"
                          >
                            {{ getProvinceName(province) }}
                          </option>
                        </select>
                      </div>
                    </div>

                    <!-- District -->
                    <div class="col-md-6">
                      <label class="form-label custom-label">
                        {{ t("find_location.district") }}
                      </label>
                      <div class="input-icon-wrap">
                        <i class="fa-solid fa-building input-icon"></i>
                        <select
                          v-model="form.district_code"
                          class="form-select custom-select"
                          :disabled="!form.province_code"
                          @change="onDistrictChange"
                        >
                          <option value="">{{ t("find_location.selectDistrict") }}</option>
                          <option
                            v-for="district in filteredDistricts"
                            :key="district.id"
                            :value="district.district_code"
                          >
                            {{ getDistrictName(district) }}
                          </option>
                        </select>
                      </div>
                    </div>

                    <!-- Commune -->
                    <div class="col-md-6">
                      <label class="form-label custom-label">
                        {{ t("find_location.commune") }}
                      </label>
                      <div class="input-icon-wrap">
                        <i class="fa-solid fa-map-pin input-icon"></i>
                        <select
                          v-model="form.commune_code"
                          class="form-select custom-select"
                          :disabled="!form.district_code"
                          @change="onCommuneChange"
                        >
                          <option value="">{{ t("find_location.selectCommune") }}</option>
                          <option
                            v-for="commune in filteredCommunes"
                            :key="commune.id"
                            :value="commune.commune_code"
                          >
                            {{ getCommuneName(commune) }}
                          </option>
                        </select>
                      </div>
                    </div>

                    <!-- OD -->
                    <div class="col-md-6">
                      <label class="form-label custom-label">
                        {{ t("find_location.od") }}
                      </label>
                      <div class="input-icon-wrap">
                        <i class="fa-solid fa-hospital input-icon"></i>
                        <select
                          v-model="form.od_code"
                          class="form-select custom-select"
                          :disabled="!form.commune_code"
                          @change="onODChange"
                        >
                          <option value="">{{ t("find_location.selectOD") }}</option>
                          <option
                            v-for="od in filteredODs"
                            :key="od.id"
                            :value="od.od_code"
                          >
                            {{ getODName(od) }}
                          </option>
                        </select>
                      </div>
                    </div>

                    <!-- HC -->
                    <div class="col-md-6">
                      <label class="form-label custom-label">
                        {{ t("find_location.hc") }}
                      </label>
                      <div class="input-icon-wrap">
                        <i class="fa-solid fa-house-medical input-icon"></i>
                        <select
                          v-model="form.hc_code"
                          class="form-select custom-select"
                          :disabled="!form.od_code"
                          @change="onHCChange"
                        >
                          <option value="">{{ t("find_location.selectHC") }}</option>
                          <option
                            v-for="hc in filteredHCs"
                            :key="hc.id"
                            :value="hc.hc_code"
                          >
                            {{ getHCName(hc) }}
                          </option>
                        </select>
                      </div>
                    </div>

                    <!-- Village -->
                    <div class="col-md-6">
                      <label class="form-label custom-label">
                        {{ t("find_location.village") }}
                      </label>
                      <div class="input-icon-wrap">
                        <i class="fa-solid fa-house-chimney input-icon"></i>
                        <select
                          v-model="form.village_code"
                          class="form-select custom-select"
                          :disabled="!form.hc_code"
                        >
                          <option value="">{{ t("find_location.selectVillage") }}</option>
                          <option
                            v-for="village in filteredVillages"
                            :key="village.id"
                            :value="village.village_code"
                          >
                            {{ getVillageName(village) }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="action-group mt-4">
                    <button class="btn btn-search-modern" @click="searchLocation">
                      <i class="fa-solid fa-magnifying-glass-location me-2"></i>
                      {{ t("find_location.search") }}
                    </button>

                    <button class="btn btn-picture-modern" @click="showPictureOnly">
                      <i class="fa-regular fa-image me-2"></i>
                      {{ t("find_location.picture") }}
                    </button>

                    <button class="btn btn-clear-modern" @click="resetFilters">
                      <i class="fa-solid fa-broom me-2"></i>
                      {{ t("find_location.clear") }}
                    </button>
                  </div>
                </div>
              </div>

              <!-- Summary -->
              <div class="col-lg-4">
                <div class="content-card h-100 summary-card">
                  <div class="card-section-header mb-3">
                    <div>
                      <h5 class="section-title mb-1">
                        <i class="fa-solid fa-list-check me-2"></i>
                        {{ t("find_location.selectedResult") }}
                      </h5>
                      <p class="section-subtitle mb-0">
                        {{ t("find_location.currentSearch") }}
                      </p>
                    </div>
                  </div>

                  <div class="summary-list">
                    <div class="summary-row">
                      <span class="summary-key">{{ t("find_location.province") }}</span>
                      <span class="summary-val">{{ selectedProvinceName || "-" }}</span>
                    </div>

                    <div class="summary-row">
                      <span class="summary-key">{{ t("find_location.district") }}</span>
                      <span class="summary-val">{{ selectedDistrictName || "-" }}</span>
                    </div>

                    <div class="summary-row">
                      <span class="summary-key">{{ t("find_location.commune") }}</span>
                      <span class="summary-val">{{ selectedCommuneName || "-" }}</span>
                    </div>

                    <div class="summary-row">
                      <span class="summary-key">{{ t("find_location.od") }}</span>
                      <span class="summary-val">{{ selectedODName || "-" }}</span>
                    </div>

                    <div class="summary-row">
                      <span class="summary-key">{{ t("find_location.hc") }}</span>
                      <span class="summary-val">{{ selectedHCName || "-" }}</span>
                    </div>

                    <div class="summary-row border-0 pb-0">
                      <span class="summary-key">{{ t("find_location.village") }}</span>
                      <span class="summary-val">{{ selectedVillageName || "-" }}</span>
                    </div>
                  </div>

                  <div class="status-box mt-4">
                    <div class="status-box-label">
                      <i class="fa-solid fa-circle-info me-2"></i>
                      {{ t("find_location.currentSearch") }}
                    </div>
                    <div class="status-box-value">
                      {{ resultMessage }}
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Map -->
            <div v-if="activeView === 'map'" class="content-card mt-4 result-card">
              <div class="card-section-header">
                <div>
                  <h5 class="section-title mb-1">
                    <i class="fa-solid fa-map-location-dot me-2"></i>
                    {{ t("find_location.googleMap") }}
                  </h5>
                  <p class="section-subtitle mb-0">
                    {{ resultMessage }}
                  </p>
                </div>

                <span class="badge badge-soft">
                  {{ mapLat && mapLng ? t("find_location.locationLoaded") : t("find_location.noLocation") }}
                </span>
              </div>

              <div v-if="mapLat && mapLng" class="map-frame-wrap">
                <iframe
                  :src="googleMapUrl"
                  width="100%"
                  height="620"
                  class="map-frame"
                  allowfullscreen
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
              </div>

              <div v-else class="alert custom-alert warning-alert mb-0 text-center">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                {{ t("find_location.noMapData") }}
                <b>{{ t("find_location.search") }}</b>
              </div>
            </div>

            <!-- Picture -->
            <div v-if="activeView === 'picture'" class="content-card mt-4 result-card">
              <div class="card-section-header">
                <div>
                  <h5 class="section-title mb-1">
                    <i class="fa-solid fa-image me-2"></i>
                    {{ t("find_location.locationPicture") }}
                  </h5>
                  <p class="section-subtitle mb-0">
                    {{ resultMessage }}
                  </p>
                </div>

                <span class="badge badge-soft">
                  {{ pictureUrl ? t("find_location.imageLoaded") : t("find_location.noImage") }}
                </span>
              </div>

              <div v-if="pictureUrl" class="image-preview-wrap text-center">
                <img
                  :src="pictureUrl"
                  :alt="t('find_location.locationImage')"
                  class="location-image"
                />
              </div>

              <div v-else class="alert custom-alert info-alert mb-0 text-center">
                <i class="fa-solid fa-image me-2"></i>
                {{ t("find_location.noImageFoundMessage") }}
                <b>{{ t("find_location.od") }}</b>
                {{ t("find_location.or") }}
                <b>{{ t("find_location.hc") }}</b>
                {{ t("find_location.andClick") }}
                <b>{{ t("find_location.picture") }}</b>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios"
import { useI18n } from "vue-i18n"

export default {
  name: "FindLocation",
  setup() {
    const { t, locale } = useI18n()
    return { t, locale }
  },

  data() {
    return {
      form: {
        province_code: "",
        district_code: "",
        commune_code: "",
        od_code: "",
        hc_code: "",
        village_code: "",
      },

      provinces: [],
      districts: [],
      communes: [],
      ods: [],
      healthCenters: [],
      villages: [],

      filteredDistricts: [],
      filteredCommunes: [],
      filteredODs: [],
      filteredHCs: [],
      filteredVillages: [],

      resultMessage: "",
      mapLat: null,
      mapLng: null,
      pictureUrl: "",
      activeView: "",
    }
  },

  computed: {
    selectedProvinceName() {
      const item = this.provinces.find(
        (x) => String(x.province_code) === String(this.form.province_code)
      )
      return item ? this.getProvinceName(item) : ""
    },

    selectedDistrictName() {
      const item = this.filteredDistricts.find(
        (x) => String(x.district_code) === String(this.form.district_code)
      )
      return item ? this.getDistrictName(item) : ""
    },

    selectedCommuneName() {
      const item = this.filteredCommunes.find(
        (x) => String(x.commune_code) === String(this.form.commune_code)
      )
      return item ? this.getCommuneName(item) : ""
    },

    selectedODName() {
      const item = this.filteredODs.find(
        (x) => String(x.od_code) === String(this.form.od_code)
      )
      return item ? this.getODName(item) : ""
    },

    selectedHCName() {
      const item = this.filteredHCs.find(
        (x) => String(x.hc_code) === String(this.form.hc_code)
      )
      return item ? this.getHCName(item) : ""
    },

    selectedVillageName() {
      const item = this.filteredVillages.find(
        (x) => String(x.village_code) === String(this.form.village_code)
      )
      return item ? this.getVillageName(item) : ""
    },

    googleMapUrl() {
      if (!this.mapLat || !this.mapLng) return ""
      return `https://www.google.com/maps?q=${this.mapLat},${this.mapLng}&z=15&output=embed`
    },
  },

  async mounted() {
    this.resultMessage = this.t("find_location.pleaseSelectLocationFilter")
    await this.loadProvinces()
    await this.loadDistricts()
    await this.loadCommunes()
    await this.loadODs()
    await this.loadHealthCenters()
    await this.loadVillages()
  },

  methods: {
    getProvinceName(item) {
      return this.locale === "km" ? item.province_name_kh : item.province_name_en
    },

    getDistrictName(item) {
      return this.locale === "km" ? item.district_name_kh : item.district_name_en
    },

    getCommuneName(item) {
      return this.locale === "km" ? item.commune_name_kh : item.commune_name_en
    },

    getODName(item) {
      return this.locale === "km" ? item.od_name_kh : item.od_name_en
    },

    getHCName(item) {
      return this.locale === "km" ? item.hc_name_kh : item.hc_name_en
    },

    getVillageName(item) {
      return this.locale === "km" ? item.village_name_kh : item.village_name_en
    },

    async loadProvinces() {
      try {
        const res = await axios.get("/get-provinces")
        this.provinces = res.data.data || []
      } catch (error) {
        console.error("Failed to load provinces", error)
      }
    },

    async loadDistricts() {
      try {
        const res = await axios.get("/get-districts")
        this.districts = res.data.data || []
      } catch (error) {
        console.error("Failed to load districts", error)
      }
    },

    async loadCommunes() {
      try {
        const res = await axios.get("/get-communes")
        this.communes = res.data.data || []
      } catch (error) {
        console.error("Failed to load communes", error)
      }
    },

    async loadODs() {
      try {
        const res = await axios.get("/ods")
        this.ods = res.data.data || []
      } catch (error) {
        console.error("Failed to load ODs", error)
      }
    },

    async loadHealthCenters() {
      try {
        const res = await axios.get("/health-centers")
        this.healthCenters = res.data.data || []
      } catch (error) {
        console.error("Failed to load health centers", error)
      }
    },

    async loadVillages() {
      try {
        const res = await axios.get("/villages")
        this.villages = res.data.data || []
      } catch (error) {
        console.error("Failed to load villages", error)
      }
    },

    onProvinceChange() {
      this.form.district_code = ""
      this.form.commune_code = ""
      this.form.od_code = ""
      this.form.hc_code = ""
      this.form.village_code = ""

      this.filteredDistricts = this.districts.filter(
        (d) => String(d.province_code) === String(this.form.province_code)
      )

      this.filteredCommunes = []
      this.filteredODs = []
      this.filteredHCs = []
      this.filteredVillages = []

      this.clearResult()
    },

    onDistrictChange() {
      this.form.commune_code = ""
      this.form.od_code = ""
      this.form.hc_code = ""
      this.form.village_code = ""

      this.filteredCommunes = this.communes.filter(
        (c) => String(c.district_code) === String(this.form.district_code)
      )

      this.filteredODs = []
      this.filteredHCs = []
      this.filteredVillages = []

      this.clearResult()
    },

    onCommuneChange() {
      this.form.od_code = ""
      this.form.hc_code = ""
      this.form.village_code = ""

      this.filteredODs = this.ods.filter(
        (o) => String(o.commune_code) === String(this.form.commune_code)
      )

      this.filteredHCs = []
      this.filteredVillages = []

      this.clearResult()
    },

    onODChange() {
      this.form.hc_code = ""
      this.form.village_code = ""

      this.filteredHCs = this.healthCenters.filter(
        (h) => String(h.od_code) === String(this.form.od_code)
      )

      this.filteredVillages = []

      this.clearResult()
    },

    onHCChange() {
      this.form.village_code = ""

      this.filteredVillages = this.villages.filter(
        (v) => String(v.hc_code) === String(this.form.hc_code)
      )

      this.clearResult()
    },

    clearResult() {
      this.mapLat = null
      this.mapLng = null
      this.pictureUrl = ""
      this.activeView = ""
      this.resultMessage = this.t("find_location.pleaseSelectLocationFilter")
    },

    async searchLocation() {
      try {
        const res = await axios.get("/find-location/search", {
          params: this.form,
        })

        const target = res.data?.data?.target

        if (target?.latitude && target?.longitude) {
          this.mapLat = target.latitude
          this.mapLng = target.longitude
          this.activeView = "map"
          this.pictureUrl = ""
          this.resultMessage = this.t("find_location.locationFoundSuccessfully")
        } else {
          this.mapLat = null
          this.mapLng = null
          this.activeView = "map"
          this.pictureUrl = ""
          this.resultMessage = this.t("find_location.noCoordinatesFound")
        }
      } catch (error) {
        console.error("Failed to search location", error)
        this.mapLat = null
        this.mapLng = null
        this.pictureUrl = ""
        this.activeView = "map"
        this.resultMessage = this.t("find_location.loadGoogleMapFailed")
      }
    },

    async showPictureOnly() {
      try {
        const res = await axios.get("/find-location/search", {
          params: this.form,
        })

        const picture = res.data?.data?.picture || ""

        this.mapLat = null
        this.mapLng = null
        this.activeView = "picture"

        if (picture) {
          this.pictureUrl = picture
          this.resultMessage = this.t("find_location.imageLoadedSuccessfully")
        } else {
          this.pictureUrl = ""
          this.resultMessage = this.t("find_location.noImageFound")
        }
      } catch (error) {
        console.error("Failed to load picture", error)
        this.mapLat = null
        this.mapLng = null
        this.pictureUrl = ""
        this.activeView = "picture"
        this.resultMessage = this.t("find_location.loadPictureFailed")
      }
    },

    resetFilters() {
      this.form = {
        province_code: "",
        district_code: "",
        commune_code: "",
        od_code: "",
        hc_code: "",
        village_code: "",
      }

      this.filteredDistricts = []
      this.filteredCommunes = []
      this.filteredODs = []
      this.filteredHCs = []
      this.filteredVillages = []

      this.mapLat = null
      this.mapLng = null
      this.pictureUrl = ""
      this.activeView = ""

      this.resultMessage = this.t("find_location.filterResetSuccessfully")
    },
  },
}
</script>

<style scoped>
.find-location-page {
  min-height: 100vh;
  background:
    radial-gradient(circle at top right, rgba(59, 130, 246, 0.10), transparent 24%),
    radial-gradient(circle at bottom left, rgba(148, 163, 184, 0.10), transparent 28%),
    linear-gradient(180deg, #f3f6fa 0%, #eef2f6 100%);
  font-family: "Khmer OS Battambang", sans-serif;
}

/* 60% white */
.main-shell {
  border-radius: 28px;
  background: rgba(255, 255, 255, 0.96);
  backdrop-filter: blur(10px);
  box-shadow: 0 24px 60px rgba(15, 23, 42, 0.10);
}

.main-header {
  position: relative;
  padding: 1.5rem 1.5rem;
  background:
    linear-gradient(135deg, rgba(59, 130, 246, 0.10), rgba(255, 255, 255, 0.95)),
    linear-gradient(135deg, #ffffff, #f8fbff);
  border-bottom: 1px solid rgba(203, 213, 225, 0.85);
}

.header-brand {
  flex-wrap: wrap;
}

.brand-logo-wrap {
  width: 72px;
  height: 72px;
  border-radius: 20px;
  display: grid;
  place-items: center;
  background: linear-gradient(135deg, #ffffff, #eff6ff);
  box-shadow: 0 10px 24px rgba(59, 130, 246, 0.12);
  flex-shrink: 0;
}

.brand-logo {
  width: 48px;
  height: 48px;
  object-fit: contain;
}

/* 30% blue */
.header-chip {
  display: inline-flex;
  align-items: center;
  padding: 0.5rem 0.9rem;
  border-radius: 999px;
  background: rgba(59, 130, 246, 0.12);
  color: #1d4ed8;
  font-size: 0.82rem;
  font-weight: 700;
}

.header-title {
  font-size: 1.65rem;
  font-weight: 800;
  color: #0f172a;
}

/* 10% gray */
.header-subtitle {
  color: #64748b;
  font-size: 0.96rem;
}

.content-card {
  background: #ffffff;
  border: 1px solid rgba(226, 232, 240, 0.95);
  border-radius: 24px;
  padding: 1.4rem;
  box-shadow: 0 14px 34px rgba(15, 23, 42, 0.05);
  transition: all 0.25s ease;
}

.content-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 18px 40px rgba(59, 130, 246, 0.08);
}

.card-section-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 1.25rem;
  flex-wrap: wrap;
}

.section-title {
  font-size: 1.08rem;
  font-weight: 800;
  color: #0f172a;
}

.section-subtitle {
  font-size: 0.92rem;
  color: #64748b;
}

.badge-soft {
  padding: 0.55rem 0.9rem;
  border-radius: 999px;
  background: #f8fbff;
  border: 1px solid #dbeafe;
  color: #2563eb;
  font-weight: 700;
  font-size: 0.8rem;
}

.custom-label {
  font-weight: 700;
  color: #334155;
  margin-bottom: 0.55rem;
}

.input-icon-wrap {
  position: relative;
}

.input-icon {
  position: absolute;
  top: 50%;
  left: 14px;
  transform: translateY(-50%);
  color: #94a3b8;
  z-index: 2;
  pointer-events: none;
}

.custom-select {
  min-height: 50px;
  border-radius: 16px;
  border: 1px solid #cbd5e1;
  padding-left: 2.6rem;
  font-size: 0.95rem;
  background-color: #ffffff;
  transition: all 0.2s ease;
  box-shadow: none;
  color: #0f172a;
}

.custom-select:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.18);
}

.custom-select:disabled {
  background-color: #f8fafc;
  opacity: 1;
  cursor: not-allowed;
}

.action-group {
  display: flex;
  flex-wrap: wrap;
  gap: 0.85rem;
}

.btn-search-modern,
.btn-picture-modern,
.btn-clear-modern,
.btn-reset-modern {
  border: 0;
  border-radius: 16px;
  font-weight: 700;
  min-height: 46px;
  padding: 0.72rem 1.2rem;
  transition: all 0.2s ease;
}

/* primary blue */
.btn-search-modern {
  color: #ffffff;
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  box-shadow: 0 10px 24px rgba(59, 130, 246, 0.24);
}

/* secondary blue-gray */
.btn-picture-modern {
  color: #ffffff;
  background: linear-gradient(135deg, #60a5fa, #475569);
  box-shadow: 0 10px 24px rgba(71, 85, 105, 0.20);
}

/* gray action */
.btn-clear-modern {
  color: #334155;
  background: #f1f5f9;
  border: 1px solid #dbe2ea;
}

.btn-reset-modern {
  color: #1d4ed8;
  background: #ffffff;
  border: 1px solid #dbeafe;
  box-shadow: 0 8px 18px rgba(59, 130, 246, 0.08);
}

.btn-search-modern:hover,
.btn-picture-modern:hover,
.btn-clear-modern:hover,
.btn-reset-modern:hover {
  transform: translateY(-1px);
}

.summary-card {
  background:
    linear-gradient(180deg, rgba(239, 246, 255, 0.75), rgba(255, 255, 255, 1));
}

.summary-list {
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  padding: 0.95rem 0;
  border-bottom: 1px dashed #dbe2ea;
}

.summary-key {
  color: #64748b;
  font-weight: 600;
}

.summary-val {
  color: #0f172a;
  font-weight: 800;
  text-align: right;
  word-break: break-word;
}

.status-box {
  border-radius: 18px;
  padding: 1rem;
  background: linear-gradient(135deg, #f8fbff, #ffffff);
  border: 1px solid #dbeafe;
}

.status-box-label {
  color: #2563eb;
  font-size: 0.85rem;
  font-weight: 700;
  margin-bottom: 0.45rem;
}

.status-box-value {
  color: #1f2937;
  font-weight: 700;
  line-height: 1.5;
}

.result-card {
  overflow: hidden;
}

.map-frame-wrap {
  border-radius: 20px;
  overflow: hidden;
  border: 1px solid #dbe2ea;
}

.map-frame {
  border: 0;
  display: block;
}

.image-preview-wrap {
  padding: 0.5rem 0;
}

.location-image {
  width: 100%;
  max-height: 560px;
  object-fit: contain;
  border-radius: 20px;
  border: 1px solid #dbe2ea;
  box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
  background: #ffffff;
}

.custom-alert {
  border-radius: 20px;
  padding: 1.2rem 1rem;
  font-weight: 600;
  border-width: 1px;
  border-style: solid;
}

.info-alert {
  color: #1d4ed8;
  background: linear-gradient(135deg, #eff6ff, #f8fbff);
  border-color: #bfdbfe;
}

.warning-alert {
  color: #475569;
  background: linear-gradient(135deg, #f8fafc, #ffffff);
  border-color: #dbe2ea;
}

@media (max-width: 991.98px) {
  .main-header {
    padding: 1.25rem;
  }

  .content-card {
    padding: 1.1rem;
  }

  .header-title {
    font-size: 1.35rem;
  }

  .map-frame {
    height: 480px;
  }
}

@media (max-width: 575.98px) {
  .brand-logo-wrap {
    width: 60px;
    height: 60px;
  }

  .brand-logo {
    width: 40px;
    height: 40px;
  }

  .header-title {
    font-size: 1.15rem;
  }

  .header-subtitle {
    font-size: 0.88rem;
  }

  .summary-row {
    flex-direction: column;
    align-items: flex-start;
  }

  .summary-val {
    text-align: left;
  }

  .action-group .btn-search-modern,
  .action-group .btn-picture-modern,
  .action-group .btn-clear-modern {
    width: 100%;
  }

  .map-frame {
    height: 380px;
  }
}
</style>
