<template>
  <div class="skb-body container">
    <div class="">
      <div class="container-fluid">
        <h3 class="fontUbuntuItalic orange-skb text-center p-1">
          {{ $t('company.title_create_company') }}
        </h3>
        <div>
          <b-card
            overlay
            img-src="https://picsum.photos/900/250/?image=3"
            img-alt="Card Image"
            text-variant="black"
            title="Que signifie référencer son entreprise sur Sakabay ?"
          >
            <b-card-text>
              Choisissez votre formule et détachez vous par votre perfectionisme .
            </b-card-text>
          </b-card>
        </div>
        <button
          class="btn button_skb_yellow"
          @click="$goTo('/entreprise/new')"
        >
          {{ $t('company.title_create_company') }}
        </button>
      </div>
      <div />
    </div>
  </div>
</template>
<script>
  export default {

  };
</script>
