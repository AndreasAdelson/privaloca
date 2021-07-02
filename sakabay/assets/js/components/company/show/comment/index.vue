<template>
  <div class="row mt-3 card-skb">
    <div class="col-12">
      <div class="title-card-skb">
        <font-awesome-icon
          class="yellow-login-skb"
          :icon="['fas', 'info-circle']"
        />
        <span class="fontPoppins underline">{{ $t('company.comment.title') }}</span>
      </div>
      <div class="comment-card">
        <div
          v-for="(comment, index) in company.comments"
          :key="'comment_' + index"
        >
          <div class="row mb-3">
            <div class="col-1">
              <b-img
                :src="urlImageProfil(comment)"
                class="rounded-circle logo-size-75"
              />
            </div>
            <div class="col-11">
              <div class="row">
                <div class="col-12">
                  {{ getName(comment) }}
                </div>
              </div>
              {{ comment.message }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    components: {
    },
    props: {
      company: {
        type: Object,
        default: null
      }
    },
    methods: {
      urlImageProfil(comment) {
        let url = '';
        if (comment.author_company && comment.author_company.image_profil) {
          url += '/build/images/uploads/' + comment.author_company.url_name + '/' + comment.author_company.image_profil;
        } else if (!comment.author_company && comment.utilisateur.image_profil) {
          url += '/build/images/uploads/' + this.company.utilisateur.image_profil;
        } else {
          url += '/build/logo.png';
        }
        return url;
      },
      getName(comment) {
        let name = '';
        if (comment.author_company) {
          name += comment.author_company.name;
        } else {
          name += comment.utilisateur.username;
        }
        return name;
      }
    }
  };
</script>
