<template>
    <app-layout title="Index">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Affiliates
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <jet-table class="text-sm">
                        <template #header>
                            <tr class="bg-gray-800 text-white">
                                <th class="px-2 py-4 w-16">
                                    <jet-checkbox v-model:checked="selectAll" />
                                </th>
                                <th class="px-2 py-4 text-left">ID</th>
                                <th class="px-2 py-4 text-left">Name</th>
                                <th class="px-2 py-4 text-left">Latitude</th>
                                <th class="px-2 py-4 text-left">Longitude</th>
                                <th class="px-2 py-4 text-left">Distance</th>
                                <th class="px-2 py-4"></th>
                            </tr>
                        </template>
                        <template #body>
                            <tr v-for="resource in affiliates" :key="resource.affiliate_id" class="border border-black-600">
                                <td class="px-2 py-4 text-center">
                                    <jet-checkbox :value="resource" v-model:checked="collectionSelected" />
                                </td>
                                <td class="px-2 py-4 text-left">{{ resource.affiliate_id }}</td>
                                <td class="px-2 py-4 text-left">{{ resource.name }}</td>
                                <td class="px-2 py-4 text-left">{{ resource.latitude }}</td>
                                <td class="px-2 py-4 text-left">{{ resource.longitude }}</td>
                                <td class="px-2 py-4 text-left">{{ (resource.distance / 1000).toFixed(2) }} Km</td>
                                <!--<td class="px-2 py-4">
                                    <div class="inline-flex items-center">
                                        <button v-if="resource.attributes.is_bookable"
                                            @click="resourceBeingBooked=resource"
                                            class="inline-flex appearance-none cursor-pointer hover:text-primary mr-3">
                                            <ClockIcon class="h-6 w-6" />
                                        </button>
                                        <inertia-link v-if="resource.permissions.canView"
                                            :href="route('locations.show', resource.attributes.id)"
                                            class="inline-flex cursor-pointer text-70 hover:text-primary mr-3">
                                            <EyeIcon class="h-6 w-6" />
                                        </inertia-link >
                                        <button v-if="resource.permissions.canDelete"
                                            @click="resourceBeingDeleted=resource"
                                            class="inline-flex appearance-none cursor-pointer hover:text-primary mr-3">
                                            <TrashIcon class="h-6 w-6" />
                                        </button>
                                    </div>
                                </td>-->
                            </tr>
                        </template>
                    </jet-table>

                    <!--<pagination v-bind="meta" />-->
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetCheckbox from "@/Jetstream/Checkbox";
    import JetTable from "@/Jetstream/Table";

    export default defineComponent({
        components: {
            AppLayout,
            JetCheckbox,
            JetTable
        },
        props: ['affiliates'],
        data() {
            return {
                collectionSelected: []
            }
        },

        computed: {
            selectAll: {
                get() {
                    return this.affiliates ? this.collectionSelected.length == this.affiliates.length : false;
                },
                set(value) {
                    (value) ? this.collectionSelected = this.affiliates : this.collectionSelected = [];
                }
            }
        }
    })
</script>
