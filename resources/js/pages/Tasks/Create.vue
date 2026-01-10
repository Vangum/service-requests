<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {Field, FieldDescription, FieldError, FieldGroup, FieldLabel, FieldLegend, FieldSet,} from '@/components/ui/field';
import {Select, SelectContent, SelectItem, SelectTrigger, SelectValue,} from '@/components/ui/select';
import {Head, router, useForm} from '@inertiajs/vue3';
import {Button} from "@/components/ui/button";
import {Textarea} from '@/components/ui/textarea';
import {Input} from "@/components/ui/input";
import {tasksIndex, tasksStore} from "@/routes";

interface User {
    id: number;
    name: string;
}

const {workers} = defineProps<{
    workers: User[];
}>();

const form = useForm({
    description: '',
    completion_notes: '',
    assigned_to: null,
    location: '',
    scheduled_at: '',
})
</script>

<template>
    <Head title="Запланировать событие" />
    
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 md:px-36 xl:px-64">
            <form @submit.prevent="form.post(tasksStore().url)">
                <FieldGroup>
                    <FieldSet>
                        <FieldLegend>Запланировать событие</FieldLegend>
                        <FieldDescription>
                            Пожалуйста, заполните все необходимые поля, чтобы запланировать событие.
                        </FieldDescription>
                        
                        <FieldGroup class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Описание задачи -->
                            <Field :data-invalid="!!form.errors.description" class="sm:col-span-3">
                                <FieldLabel for="description">Описание задачи*</FieldLabel>
                                <Textarea v-model="form.description" :aria-invalid="!!form.errors.description" id="description" class="resize-none" required />
                                <FieldError v-if="form.errors.description" :errors="[form.errors.description]" />
                            </Field>
                            
                            <!-- Примечания -->
                            <Field :data-invalid="!!form.errors.completion_notes" class="sm:col-span-3">
                                <FieldLabel for="completion_notes">Примечания</FieldLabel>
                                <Textarea v-model="form.completion_notes" :aria-invalid="!!form.errors.completion_notes" id="completion_notes" class="resize-none" />
                                <FieldError v-if="form.errors.completion_notes" :errors="[form.errors.completion_notes]" />
                            </Field>
                        </FieldGroup>
                        
                        <FieldGroup class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Лаборант -->
                            <Field :data-invalid="!!form.errors.assigned_to" class="sm:col-span-3">
                                <FieldLabel>Лаборант*</FieldLabel>
                                <Select v-model="form.assigned_to" :aria-invalid="!!form.errors.assigned_to" :disabled="workers.length === 0" required>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Назначьте лаборанта" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="worker in workers" :key="worker.id" :value="worker.id">
                                            {{ worker.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <FieldError v-if="form.errors.assigned_to" :errors="[form.errors.assigned_to]" />
                            </Field>
                            
                            <!-- Дата и время проведения -->
                            <Field :data-invalid="!!form.errors.scheduled_at" class="sm:col-span-3">
                                <FieldLabel for="scheduled_at">Дата и время проведения*</FieldLabel>
                                <Input v-model="form.scheduled_at" :aria-invalid="!!form.errors.scheduled_at" id="scheduled_at" type="datetime-local" required />
                                <FieldError v-if="form.errors.scheduled_at" :errors="[form.errors.scheduled_at]" />
                            </Field>
                        </FieldGroup>
                        
                        <FieldGroup>
                            <!-- Место проведения -->
                            <Field :data-invalid="!!form.errors.location">
                                <FieldLabel for="location">Место проведения*</FieldLabel>
                                <Input v-model="form.location" :aria-invalid="!!form.errors.location" id="location" required />
                                <FieldError v-if="form.errors.location" :errors="[form.errors.location]" />
                            </Field>
                        </FieldGroup>
                    </FieldSet>
                    <Field orientation="horizontal" class="justify-end">
                        <Button @click="router.get(tasksIndex().url)" variant="secondary" type="button">
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
