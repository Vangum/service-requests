<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger
} from "@/components/ui/alert-dialog";
import {Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle} from '@/components/ui/dialog';
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from '@/components/ui/table';
import {MessageCircle, Pen, Trash} from 'lucide-vue-next';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import {ticketsComment, ticketsCreate, ticketsDestroy, ticketsEdit} from "@/routes";
import {Button} from "@/components/ui/button";
import {Badge} from "@/components/ui/badge";
import {HoverCard, HoverCardContent, HoverCardTrigger} from "@/components/ui/hover-card";
import {Textarea} from "@/components/ui/textarea";
import {ref} from "vue";
import {Field, FieldError, FieldLabel} from "@/components/ui/field";

interface Ticket {
    id: number;
    title: string;
    reason: string;
    location: string;
    status: 'new' | 'completed' | 'rejected';
    resolution_notes?: string;
    comment?: string;
    created_at: string;
}

const {tickets} = defineProps<{
    tickets: Ticket[];
}>();

const statusLabels: Record<string, string> = {
    'new': 'Новая',
    'completed': 'Выполнена',
    'rejected': 'Отклонена'
};

const showCommentDialog = ref(false);
const selectedTicket = ref<Ticket | null>(null);
const commentForm = useForm({
    comment: '',
});

const openCommentDialog = (ticket: Ticket) => {
    selectedTicket.value = ticket;
    commentForm.comment = ticket.comment ?? '';
    showCommentDialog.value = true;
};

const saveComment = () => {
    if (!selectedTicket.value) return;
    
    commentForm.post(ticketsComment(selectedTicket.value.id).url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            showCommentDialog.value = false;
            commentForm.reset();
            selectedTicket.value = null;
        },
    });
};
</script>

<template>
    <Head title="Мои заявки" />
    
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            
            <!-- Если нет заявок -->
            <div v-if="tickets.length === 0" class="flex items-center justify-center flex-1">
                <p>Список заявок пуст.
                    <Link :href="ticketsCreate().url" class="underline">Добавить</Link>
                </p>
            </div>
            
            <div v-else>
                <div class="flex items-center justify-between gap-8 py-4">
                    <div class="max-w-2xl">
                        <h4 class="font-medium">Список заявок</h4>
                        
                        <p class="mt-1 text-sm text-muted-foreground leading-relaxed">
                            Здесь отображаются заявки, которые вы создали.
                        </p>
                    </div>
                    
                    <Button @click="router.get(ticketsCreate().url)" class="shrink-0">Оставить заявку</Button>
                </div>
                
                <!-- Таблица -->
                <div class="rounded-md border">
                    <Table class="w-full table-fixed">
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[50px]">ID</TableHead>
                                <TableHead class="w-[250px]">Причина обращения</TableHead>
                                <TableHead class="w-[150px]">Аудитория</TableHead>
                                <TableHead class="w-[100px]">Статус</TableHead>
                                <TableHead class="w-[250px]">Заметки о выполнении</TableHead>
                                <TableHead class="w-[200px]">Комментарий</TableHead>
                                <TableHead class="w-[100px] text-right">Действия</TableHead>
                            </TableRow>
                        </TableHeader>
                        
                        <TableBody>
                            <TableRow v-for="ticket in tickets" :key="ticket.id" :class="{'opacity-50': ticket.status !== 'new'}">
                                <TableCell class="align-middle">
                                    {{ ticket.id }}
                                </TableCell>
                                
                                <!-- Причина обращения -->
                                <TableCell class="align-middle">
                                    <HoverCard v-if="ticket.reason.length >= 50">
                                        <HoverCardTrigger as-child>
                                            <div class="truncate cursor-pointer">
                                                {{ ticket.reason }}
                                            </div>
                                        </HoverCardTrigger>
                                        
                                        <HoverCardContent class="w-96">
                                            <p class="text-sm whitespace-normal break-words">
                                                {{ ticket.reason }}
                                            </p>
                                        </HoverCardContent>
                                    </HoverCard>
                                    
                                    <div v-else class="truncate">
                                        {{ ticket.reason }}
                                    </div>
                                </TableCell>
                                
                                <!-- Аудитория -->
                                <TableCell class="align-middle">
                                    <div class="truncate">
                                        {{ ticket.location }}
                                    </div>
                                </TableCell>
                                
                                <!-- Статус -->
                                <TableCell class="align-middle">
                                    <Badge
                                        :variant="ticket.status === 'new' ? 'outline' : ticket.status === 'completed' ? 'default' : 'secondary'"
                                    >
                                        {{ statusLabels[ticket.status] }}
                                    </Badge>
                                </TableCell>
                                
                                <!-- Заметки о выполнении -->
                                <TableCell class="align-middle">
                                    <HoverCard v-if="ticket.resolution_notes && ticket.resolution_notes.length >= 50">
                                        <HoverCardTrigger as-child>
                                            <div class="truncate cursor-pointer">
                                                {{ ticket.resolution_notes }}
                                            </div>
                                        </HoverCardTrigger>
                                        
                                        <HoverCardContent class="w-96">
                                            <p class="text-sm whitespace-normal break-words">
                                                {{ ticket.resolution_notes }}
                                            </p>
                                        </HoverCardContent>
                                    </HoverCard>
                                    
                                    <div v-else class="truncate">
                                        {{ ticket.resolution_notes ?? '–' }}
                                    </div>
                                </TableCell>
                                
                                <!-- Комментарий -->
                                <TableCell class="align-middle">
                                    <HoverCard v-if="ticket.comment && ticket.comment.length >= 50">
                                        <HoverCardTrigger as-child>
                                            <div class="truncate cursor-pointer">
                                                {{ ticket.comment }}
                                            </div>
                                        </HoverCardTrigger>
                                        
                                        <HoverCardContent class="w-96">
                                            <p class="text-sm whitespace-normal break-words">
                                                {{ ticket.comment }}
                                            </p>
                                        </HoverCardContent>
                                    </HoverCard>
                                    
                                    <div v-else-if="ticket.comment" class="truncate">
                                        {{ ticket.comment }}
                                    </div>
                                    
                                    <div v-else class="text-muted-foreground">
                                        –
                                    </div>
                                </TableCell>
                                
                                <TableCell class="align-middle text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Button
                                            @click="router.get(ticketsEdit(ticket.id).url)"
                                            variant="outline"
                                            size="icon"
                                            aria-label="Редактировать"
                                        >
                                            <Pen class="w-4 h-4" />
                                        </Button>
                                        
                                        <AlertDialog>
                                            <AlertDialogTrigger as-child>
                                                <Button
                                                    variant="outline"
                                                    size="icon"
                                                    aria-label="Удалить"
                                                >
                                                    <Trash class="w-4 h-4" />
                                                </Button>
                                            </AlertDialogTrigger>
                                            
                                            <AlertDialogContent>
                                                <AlertDialogHeader>
                                                    <AlertDialogTitle>
                                                        Удалить заявку?
                                                    </AlertDialogTitle>
                                                    <AlertDialogDescription>
                                                        Вы уверены, что хотите удалить эту заявку? Это действие нельзя отменить.
                                                    </AlertDialogDescription>
                                                </AlertDialogHeader>
                                                
                                                <AlertDialogFooter>
                                                    <AlertDialogCancel>
                                                        Отмена
                                                    </AlertDialogCancel>
                                                    <AlertDialogAction @click="router.delete(ticketsDestroy(ticket.id).url)">
                                                        Удалить
                                                    </AlertDialogAction>
                                                </AlertDialogFooter>
                                            </AlertDialogContent>
                                        </AlertDialog>
                                        
                                        <Button
                                            @click="openCommentDialog(ticket)"
                                            :variant="ticket.comment ? 'default' : 'outline'"
                                            size="icon"
                                            class="h-8 w-8"
                                            :disabled="ticket.status !== 'completed'"
                                            :title="
                                                ticket.status === 'completed'
                                                    ? (ticket.comment ? 'Редактировать комментарий' : 'Добавить комментарий')
                                                    : 'Комментарий доступен только для выполненных заявок'
                                            "
                                            aria-label="Комментарий"
                                        >
                                            <MessageCircle
                                                class="w-4 h-4"
                                            />
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </AppLayout>
    
    <!-- Dialog для добавления/редактирования комментария -->
    <Dialog v-model:open="showCommentDialog">
        <DialogContent v-if="selectedTicket" class="sm:max-w-[525px]">
            <form @submit.prevent="saveComment">
                <DialogHeader>
                    <DialogTitle>Комментарий к заявке</DialogTitle>
                    <DialogDescription>Добавьте ваш комментарий к выполненной заявке.</DialogDescription>
                </DialogHeader>
                
                <div class="my-8">
                    <Field :data-invalid="!!commentForm.errors.comment">
                        <FieldLabel for="reason">Комментарий</FieldLabel>
                        <Textarea v-model="commentForm.comment" :aria-invalid="!!commentForm.errors.comment" id="comment" class="resize-none" required />
                        <FieldError v-if="commentForm.errors.comment" :errors="[commentForm.errors.comment]" />
                    </Field>
                    <p class="text-xs text-muted-foreground mt-2">
                        {{ commentForm.comment.length }}/255
                    </p>
                </div>
                
                <DialogFooter>
                    <DialogClose as-child>
                        <Button variant="outline">Отмена</Button>
                    </DialogClose>
                    <Button type="submit" :disabled="commentForm.processing">
                        {{ selectedTicket.comment ? 'Обновить' : 'Добавить' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>