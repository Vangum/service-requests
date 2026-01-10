<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {Field, FieldDescription, FieldError, FieldGroup, FieldLabel, FieldLegend, FieldSet,} from '@/components/ui/field';
import {Head, router, useForm} from '@inertiajs/vue3';
import {HoverCard, HoverCardContent, HoverCardTrigger,} from '@/components/ui/hover-card';
import {Button} from "@/components/ui/button";
import {Textarea} from '@/components/ui/textarea';
import {Input} from "@/components/ui/input";
import {CircleQuestionMark} from 'lucide-vue-next';
import {ticketsIndex, ticketsUpdate} from "@/routes";

interface Ticket {
    id: number;
    title: string;
    reason: string;
    location: string;
    status: 'new' | 'completed' | 'closed';
    resolution_notes?: string;
    created_at: string;
}

const {ticket} = defineProps<{
    ticket: Ticket;
}>();

const form = useForm({
    reason: ticket.reason,
    resolution_notes: ticket.resolution_notes || '',
    location: ticket.location,
})
</script>

<template>
    <Head title="123" />
    
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 md:px-36 xl:px-64">
            <form @submit.prevent="form.put(ticketsUpdate(ticket.id).url)">
                <FieldGroup>
                    <FieldSet>
                        <FieldLegend>Редактирование заявки</FieldLegend>
                        <FieldDescription>
                            Внесите необходимые изменения и сохраните обновлённую информацию по заявке.
                        </FieldDescription>
                        
                        <FieldGroup class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Причина обращения -->
                            <Field :data-invalid="!!form.errors.reason" class="sm:col-span-3">
                                <FieldLabel for="reason">Причина обращения*</FieldLabel>
                                <Textarea v-model="form.reason" :aria-invalid="!!form.errors.reason" id="reason" class="resize-none" required />
                                <FieldError v-if="form.errors.reason" :errors="[form.errors.reason]" />
                            </Field>
                            
                            <!-- Примечания -->
                            <Field :data-invalid="!!form.errors.resolution_notes" class="sm:col-span-3">
                                <div class="space-y-[11.5px]">
                                    <div class="flex items-center gap-2">
                                        <FieldLabel for="resolution_notes">Примечания</FieldLabel>
                                        <HoverCard>
                                            <HoverCardTrigger as-child>
                                                <Button variant="ghost" size="sm" class="h-5 w-5 p-0">
                                                    <CircleQuestionMark class="w-4 h-4" />
                                                </Button>
                                            </HoverCardTrigger>
                                            <HoverCardContent class="w-80">
                                                <p class="text-sm">
                                                    Дополнительная информация, которая может помочь решить проблему. Например, опишите условия, при которых возникла проблема. Заполнять это поле не обязательно.
                                                </p>
                                            </HoverCardContent>
                                        </HoverCard>
                                    </div>
                                    <Textarea v-model="form.resolution_notes" :aria-invalid="!!form.errors.resolution_notes" id="resolution_notes" class="resize-none" />
                                </div>
                                <FieldError v-if="form.errors.resolution_notes" :errors="[form.errors.resolution_notes]" />
                            </Field>
                        </FieldGroup>
                        
                        <FieldGroup>
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
