<template>
<div class="row">
    <div class="btn-group col-md-12" role="toolbar" aria-label="...">
        <button type="button" class="btn btn-default"  v-for="letter in letters" @click="fetchProgrammes(letter)">{{ letter.toUpperCase() }}</button>
    </div>
    <div class="col-md-12">
    <div class="pagination" v-if="pagination">
        Page {{ pagination.page }} of {{ pagination.last_page }}

        <div class="btn-group" role="toolbar" aria-label="...">
            <button type="button" v-bind:disabled="page == 1" class="btn btn-default" @click="prevPage">Prev</button>
            <button type="button" v-bind:disabled="page == pagination.last_page" class="btn btn-default" @click="nextPage">Next</button>
        </div>
    </div>
    </div>
    </div>

    <div class="row" v-for="subprogrammes in programmes | chunk 4">
        <div v-for="programme in subprogrammes" class="col-md-3 col-sm-6 col-xs-12">
            <img class="img-responsive" v-bind:src="programme.image" alt="">
            <h4>{{ programme.title }}</h4>
            <p>{{ programme.synopsis }}</p>

        </div>
    </div>
</template>

<script>
    module.exports = {
        data: function() {
            return {
                letters: 'abcdefghijklmnopqrstuvwxyz'.split(''),
                page: 1,
                letter: '',
                programmes: [],
                pagination: null,
            }
        },

        methods: {
            fetchProgrammes: function(letter) {
                this.programmes = [];
                if (letter != this.letter) this.page = 1;

                this.letter = letter;
                this.$http.get('/api/programmes/' + this.letter, {page: this.page})
                .success(function(response) {
                    this.programmes = response.programmes;
                    this.pagination = response.pagination;
                }.bind(this));
            },

            prevPage: function() {
                this.page = Math.max(1, this.page - 1);
                this.fetchProgrammes(this.letter);
            },

            nextPage: function() {
                this.page = Math.min(this.pagination.last_page, this.page + 1);
                this.fetchProgrammes(this.letter);
            }
        },
    }
</script>