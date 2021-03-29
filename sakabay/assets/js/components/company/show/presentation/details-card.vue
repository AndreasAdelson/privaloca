<template>
  <div class="row mt-3 card-skb">
    <div class="col-8">
      <div class="title-card-skb">
        <font-awesome-icon
          class="yellow-login-skb"
          :icon="['fas', 'info-circle']"
        />
        <span class="fontPoppins underline">{{ $t('company.presentation.informations') }}</span>
      </div>
      <div class="details-card">
        <div class="number-line py-2">
          <font-awesome-icon
            class="green-skb"
            :icon="['fas', 'phone-alt']"
          />
          <span class="ml-2 fontAlice fontSize18">06 48 59 63 56</span>
        </div>
        <div class="number-line py-2">
          <font-awesome-icon
            class="blue-skb"
            :icon="['fas', 'map-marker-alt']"
          />
          <span class="ml-2 fontAlice fontSize18">{{ company.address.postal_address }}, {{ company.address.postal_code }} {{ company.city.name.toUpperCase() }}</span>
        </div>
        <div class="number-line py-2">
          <font-awesome-icon
            class="orange-login-skb"
            :icon="['fas', 'link']"
          />
          <span class="ml-2 fontAlice fontSize18">{{ company.address.postal_address }}, {{ company.address.postal_code }} {{ company.city.name.toUpperCase() }}</span>
        </div>
      </div>
    </div>
    <div class="col-4">
      <v-map
        :zoom="16"
        :center="[company.address.latitude, company.address.longitude]"
        style="height:300px"
      >
        <v-marker :lat-lng="[company.address.latitude, company.address.longitude]" />
        <v-tile-layer url="http://{s}.tile.osm.org/{z}/{x}/{y}.png" />
      </v-map>
    </div>
  </div>
</template>
<script>
  export default {
    props: {
      company: {
        type: Object,
        default: null
      }
    }
  };
</script>
