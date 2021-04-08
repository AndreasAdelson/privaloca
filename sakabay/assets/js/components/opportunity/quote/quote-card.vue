<template>
  <div class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-12">
          <div class="row justify-content-between mb-3 align-items-center">
            <div class="col-6 ">
              <div class="ml-2">
                <span class="bold">{{ $capitalise(pendingQuote.title) }}</span>
              </div>
            </div>
            <div class="col-1 text-right black-light-skb fontSize14">
              <span>{{ $getDateLabel(pendingQuote.dt_created) }}</span>
            </div>
          </div>
          <div class="row">
            <div class="col-11 mx-auto">
              <nl2br
                tag="p"
                :text="pendingQuote.description | truncate(500, ' ...')"
              />
            </div>
          </div>
          <div class="row my-3 justify-content-end">
            <div class="col-2">
              <a
                :href="'/opportunities/recap/' + pendingQuote.id + '/' + companySelected.url_name"
                target="_blank"
                class="btn button_skb_yellow"
              >
                <font-awesome-icon
                  class="mr-2"
                  :icon="['fas', 'file-alt']"
                />
                <span>Récapitulatif</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import moment from 'moment';

  export default {
    props: {
      pendingQuote: {
        type: Object,
        default: null
      },
      companySelected: {
        type: Object,
        default: null
      }
    },
    computed: {
      dtCreated() {
        return moment(this.pendingQuote.dt_created, 'DD/MM/YYYY H:mm:ss').format('[le] DD/MM/YYYY [à] H:mm');
      }
    },
    methods: {

    },
  };
</script>
