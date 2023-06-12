<template>
    <div>
        <h1>Messages {{ messages.length }}</h1>

        <div class="row row-cols-3">
            <div v-for="message in messages" :key="message.id" class="col p-2">
                <div class="card">
                    <div class="card-header">
                        <p class="card-subtitle text-muted">
                            <span rel="{{ message.created_at }}">{{
                                    this.$moment(message.created_at).format('HH:mm')
                                }}</span>
                            <span>&nbsp;-&nbsp;{{ message.sender.name }}</span>
                        </p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ message.content }}</p>
                    </div>
                </div>
            </div>
            <infinite-loading @infinite="infiniteHandler"></infinite-loading>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user_id'],
    data() {
        return {
            messages: [],
            page: 1
        };
    },
    mounted() {
        const channels = [
            Echo.channel('message'),
            Echo.private(`message.${this.user_id}`),
        ];

        channels.forEach(channel => {
            channel.listen('MessageCreated', (e) => {
                this.messages = [e.message].concat(this.messages);
            });
        })
    },
    methods: {
        infiniteHandler($state) {
            axios.get('/api/message', {
                params: {
                    page: this.page,
                },
            }).then(({data}) => {
                if (data.data.length) {
                    this.page += 1;
                    this.messages.push(...data.data);
                    $state.loaded();
                } else {
                    $state.complete();
                }
            });
        },
    }
};
</script>
