<template>
    <app-layout title="Index">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Affiliates
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex my-4">
                    <select v-model="meta.range" @change="updateRange"
                        class="mr-2 border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none">
                        <option value="10000">10 Km</option>
                        <option value="20000">20 Km</option>
                        <option value="30000">30 Km</option>
                        <option value="40000">40 Km</option>
                        <option value="50000">50 Km</option>
                        <option value="100000">100 Km</option>
                        <option value="200000">200 Km</option>
                        <option value="200000">500 Km</option>
                    </select>

                    <select v-model="meta.city" @change="updateCity"
                        class="mr-2 border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none">
                        <option value="dublin">Dublin</option>
                        <option value="malta">Malta</option>
                    </select>

                    <div class="text-right flex-1">
                        <input type="file" class="hidden" ref="attachment" @change="importFile">

                        <jet-button class="h-10" type="button" @click.prevent="selectFile">
                            Import affiliates
                        </jet-button>

                        <jet-input-error :message="form.errors.file" class="mt-2" />
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" v-if="!isEmpty">
                    <jet-table class="text-sm">
                        <template #header>
                            <tr class="bg-gray-800 text-white">
                                <th class="px-4 py-4 text-left">ID</th>
                                <th class="px-2 py-4 text-left">Name</th>
                                <th class="px-2 py-4 text-left">Latitude</th>
                                <th class="px-2 py-4 text-left">Longitude</th>
                                <th class="px-2 py-4 text-left">Distance</th>
                                <th class="px-2 py-4"></th>
                            </tr>
                        </template>
                        <template #body>
                            <tr v-for="resource in affiliates" :key="resource.affiliate_id" class="border border-black-600">
                                <td class="px-4 py-4 text-left">{{ resource.affiliate_id }}</td>
                                <td class="px-2 py-4 text-left">{{ resource.name }}</td>
                                <td class="px-2 py-4 text-left">{{ resource.latitude }}</td>
                                <td class="px-2 py-4 text-left">{{ resource.longitude }}</td>
                                <td class="px-2 py-4 text-left">{{ (resource.distance / 1000).toFixed(2) }} Km</td>
                            </tr>
                        </template>
                    </jet-table>
                </div>

                <!-- no result alert -->
                <div v-else class="flex items-center bg-yellow-500 text-white text-sm font-bold px-4 py-10 rounded-lg" role="alert">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/>
                    </svg>
                    <p>Ooops! No affiliates to show for search criteria. Or you can import affiliates from txt file</p>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetButton from "@/Jetstream/Button";
    import JetInput from "@/Jetstream/Input";
    import JetInputError from "@/Jetstream/InputError";
    import JetCheckbox from "@/Jetstream/Checkbox";
    import JetTable from "@/Jetstream/Table";

    export default defineComponent({
        components: {
            AppLayout,
            JetButton,
            JetInput,
            JetInputError,
            JetCheckbox,
            JetTable
        },
        props: ['affiliates', 'meta'],
        data() {
            return {
                form: this.$inertia.form({
                    attachment: null
                }),
            }
        },
        computed: {
            isEmpty() {
                return this.affiliates.length == 0;
            }
        },
        methods: {
            updateCity(ev) {
                this.filter({ city: ev.target.value })
            },
            updateRange(ev) {
                this.filter({ range: ev.target.value })
            },
            filter(data) {
                let payload = _.merge(this.meta, data);
                this.$inertia.reload({ data: payload })
            },
            selectFile() {
                this.$refs.attachment.click();
            },
            importFile() {
                if (this.$refs.attachment) {
                    this.form.attachment = this.$refs.attachment.files[0]
                }

                this.form.post(route('affiliates.import'), {
                    errorBag: 'importAffiliates',
                    preserveScroll: true
                });
            }
        }
    })
</script>
