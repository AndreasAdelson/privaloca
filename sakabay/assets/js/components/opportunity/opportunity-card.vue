<template>
  <div>
    <div class="col-12 card-body-opportunities">
      <div class="row justify-content-between mb-3 align-items-center">
        <div class="col-6 ">
          <div class="ml-2">
            <span class="bold">{{ $capitalise(this.opportunity.title) }}</span>
          </div>
        </div>
        <div class="col-1 text-right black-light-skb">
          <span>{{ $getDateLabel(this.opportunity.dt_created) }}</span>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-11 mx-auto">
          <span>{{ this.opportunity.description }}</span>
        </div>
      </div>
      <div class="row no-gutters py-2 border-top">
        <div class="col-6 text-center mx-auto">
          <button class="btn button_skb_transparent">
            <!-- <i class="far fa-handshake fa-lg mt-2 mr-1" /> -->
            <i class="far fa-hand-point-up fa-lg mt-2 mr-1" />
            <span>Se proposer</span>
          </button>
        </div>

        <!-- <div class="col-6 text-center">
          <button class="btn button_skb_transparent">
            <i class="far fa-thumbs-down fa-lg mt-2 mr-1" />
            <span>Pas intéressé</span>
          </button>
        </div> -->
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    props: {
      opportunity: {
        type: Object,
        default: () => new Object()
      }
    }
  };
</script>
