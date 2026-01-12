<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {Field, FieldDescription, FieldError, FieldGroup, FieldLabel, FieldLegend, FieldSet,} from '@/components/ui/field';
import {Head, router, useForm} from '@inertiajs/vue3';
import {Button} from "@/components/ui/button";
import {Textarea} from '@/components/ui/textarea';
import {Input} from "@/components/ui/input";
import {ticketsIndex, ticketsStore} from "@/routes";

const form = useForm({
    reason: '',
    location: '',
})
</script>

<template>
    <Head title="Оставить заявку" />
    
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 md:px-36 xl:px-64">
            <form @submit.prevent="form.post(ticketsStore().url)">
                <FieldGroup>
                    <FieldSet>
                        <FieldLegend>Оставить заявку</FieldLegend>
                        <FieldDescription>
                            Пожалуйста, заполните все необходимые поля, чтобы оставить заявку.
                        </FieldDescription>
                        
                        <FieldGroup>
                            <!-- Причина обращения -->
                            <Field :data-invalid="!!form.errors.reason">
                                <FieldLabel for="reason">Причина обращения*</FieldLabel>
                                <Textarea v-model="form.reason" :aria-invalid="!!form.errors.reason" id="reason" class="resize-none" required />
                                <FieldError v-if="form.errors.reason" :errors="[form.errors.reason]" />
                            </Field>
                            
                            <!-- Аудитория -->
                            <Field :data-invalid="!!form.errors.location">
                                <FieldLabel for="location">Аудитория*</FieldLabel>
                                <Input v-model="form.location" :aria-invalid="!!form.errors.location" id="location" required />
                                <FieldError v-if="form.errors.location" :errors="[form.errors.location]" />
                            </Field>
                        </FieldGroup>
                    </FieldSet>
                    
                    <Field orientation="horizontal" class="justify-end">
                        <Button @click="router.get(ticketsIndex().url)" variant="secondary" type="button">
                            Отмена
                        </Button>
                        <Button type="submit">
                            Отправить
                        </Button>
                    </Field>
                
                </FieldGroup>
            </form>
        </div>
    </AppLayout>
</template>
