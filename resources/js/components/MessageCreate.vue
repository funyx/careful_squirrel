<template>
    <div>
        <h2>Create Message</h2>

        <form @submit.prevent="sendMessage">
            <div class="col-md-12 mt-2">
                <div class="input-group has-validation">
                    <label class="px-2 col-md-2" for="is_private">Is&nbsp;private message&nbsp;?</label>
                    <input v-model="is_private" class="col-md-10 m-auto" type="checkbox"/>
                </div>
            </div>
            <div class="col-md-12" v-if="is_private">
                <div class="input-group has-validation">
                    <label class="px-2 col-md-2" for="recipient_id">Set Recipient</label>
                    <v-select
                        v-model="recipientId"
                        :filterable="false"
                        :options="users"
                        :reduce="user => user.id"
                        class="col-md-10"
                        label="name"
                        @search="onUserSearch"
                    ></v-select>
                    <p v-if="validationErrors?.recipient_id?.length ?? false"
                       v-for="msg in validationErrors.recipient_id"
                       class="px-4 py-1"
                       style="color:red; font-size: 80%">
                        {{ msg }}
                    </p>
                </div>
            </div>
            <div class="mt-2">
                <div class="has-validation">
                    <div class="input-group col-md-12">
                        <label class="px-2 col-md-2" for="content">Message Content:</label>
                        <textarea id="content" v-model="messageContent" class="form-control col-md-10"></textarea>
                    </div>
                    <p v-if="validationErrors?.content?.length ?? false"
                       v-for="msg in validationErrors.content"
                       class="px-4 py-1"
                       style="color:red; font-size: 80%">
                        {{ msg }}
                    </p>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Send Message</button>
        </form>
    </div>
</template>

<script>

export default {
    data() {
        return {
            is_private: false,
            messageContent: '',
            users: [],
            recipientId: null,
            lastSearchValue: '',
            searchTimer: null,
            searchDelay: 1000, // 1 sec
            validationErrors: {}
        };
    },
    watch: {
        is_private(val) {
            if (val === false) {
                this.recipientId = null;
            }
        }
    },
    methods: {
        async onUserSearch(query, loading) {
            if (query.length) {
                loading(true);
                await this.userSearch(query.trim());
                loading(false);
            }
        },
        async userSearch(query) {
            clearTimeout(this.searchTimer);
            if (query === '') return;
            if (query !== this.lastSearchValue) {
                this.searchTimer = setTimeout(async () => {
                    try {
                        const response = await axios.get('/api/user', {
                            params: {
                                query,
                            }
                        });
                        this.users = response.data;
                    } catch (error) {
                        console.error(error); // dev
                    }
                }, this.searchDelay);
                this.lastSearchValue = query;
            }
        },
        async sendMessage() {
            try {
                await axios.post('/api/message', {
                    content: this.messageContent,
                    recipient_id: this.recipientId,
                });
                this.messageContent = '';
                this.recipientId = null;
            } catch (error) {
                if (error.response?.status === 400) {
                    this.validationErrors = error.response?.data?.errors;
                } else {
                    console.error(error) // dev
                }
            }
        },
    },
};
</script>
