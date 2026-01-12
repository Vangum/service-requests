<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from '@/components/ui/table';
import {Check, X} from 'lucide-vue-next';
import {Head, router, useForm} from '@inertiajs/vue3';
import {cancelledTask, closedTicket, completeTask, completeTicket} from "@/routes";
import {Button} from "@/components/ui/button";
import {Badge} from "@/components/ui/badge";
import {Textarea} from '@/components/ui/textarea';
import {HoverCard, HoverCardContent, HoverCardTrigger} from "@/components/ui/hover-card";
import {Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle} from '@/components/ui/dialog';
import {ref} from "vue";

interface User {
    id: number;
    name: string;
}

interface Ticket {
    id: number;
    title: string;
    reason: string;
    location: string;
    status: 'new' | 'completed' | 'rejected';
    completion_notes?: string;
    comment?: string;
    created_at: string;
    user: User;
}

interface Task {
    id: number;
    title: string;
    description: string;
    location: string;
    scheduled_at: string;
    scheduled_at_formatted: string;
    status: 'new' | 'completed' | 'rejected';
    completion_notes?: string;
    assignee?: {
        id: number;
        name: string;
    };
    teacher: User;
}

const {tickets, tasks} = defineProps<{
    tickets: Ticket[];
    tasks: Task[];
}>();

const ticketStatusLabels: Record<string, string> = {
    'new': 'Новая',
    'completed': 'Выполнена',
    'rejected': 'Закрыта'
};

const taskStatusLabels: Record<string, string> = {
    'new': 'Запланировано',
    'completed': 'Завершено',
    'rejected': 'Отменено'
};

const showTicketDialog = ref(false);
const selectedTicket = ref<Ticket | null>(null);
const ticketForm = useForm({
    completion_notes: '',
});

const showTaskDialog = ref(false);
const selectedTask = ref<Task | null>(null);
const taskForm = useForm({
    completion_notes: '',
});

const openTicketDialog = (ticket: Ticket) => {
    selectedTicket.value = ticket;
    ticketForm.completion_notes = ticket.completion_notes ?? '';
    showTicketDialog.value = true;
};

const openTaskDialog = (task: Task) => {
    selectedTask.value = task;
    taskForm.completion_notes = task.completion_notes ?? '';
    showTaskDialog.value = true;
};

const updateTicketStatus = () => {
    if (!selectedTicket.value) return;
    
    ticketForm.put(completeTicket(selectedTicket.value.id).url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            showTicketDialog.value = false;
            ticketForm.reset();
            selectedTicket.value = null;
        },
    });
};

const updateTaskStatus = () => {
    if (!selectedTask.value) return;
    
    taskForm.put(completeTask(selectedTask.value.id).url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            showTaskDialog.value = false;
            taskForm.reset();
            selectedTask.value = null;
        }
    });
};
</script>

<template>
    <Head title="Заявки и события" />
    
    <AppLayout>
        <div class="two-tables-container gap-4 p-4">
            <div class="flex flex-col gap-4 flex-1 min-h-0 overflow-hidden">
                <!-- ЗАЯВКИ -->
                <div class="flex-1 flex flex-col min-h-0">
                    <div class="flex-shrink-0 py-4 pr-4">
                        <h4 class="font-medium">Список заявок</h4>
                        <p class="mt-1 text-sm text-muted-foreground leading-relaxed">
                            Заявки, оставленные пользователями.
                        </p>
                    </div>
                    
                    <div v-if="tickets.length === 0" class="flex-1 flex items-center justify-center text-muted-foreground">
                        Нет заявок
                    </div>
                    
                    <div v-else class="flex-1 rounded-md border overflow-hidden flex flex-col min-h-0">
                        <Table class="w-full table-fixed border-collapse">
                            <TableHeader class="sticky top-0 bg-white z-20">
                                <TableRow class="border-b">
                                    <TableHead class="w-[50px]">ID</TableHead>
                                    <TableHead class="w-[180px]">Причина</TableHead>
                                    <TableHead class="w-[100px]">Имя</TableHead>
                                    <TableHead class="w-[130px]">Заметки</TableHead>
                                    <TableHead class="w-[100px]">Комментарий</TableHead>
                                    <TableHead class="w-[100px]">Аудитория</TableHead>
                                    <TableHead class="w-[80px]">Статус</TableHead>
                                    <TableHead class="w-[100px] text-right">Действия</TableHead>
                                </TableRow>
                            </TableHeader>
                            
                            <TableBody>
                                <TableRow v-for="ticket in tickets" :key="ticket.id" :class="{'opacity-50 pointer-events-none': ticket.status !== 'new'}">
                                    <TableCell class="align-middle">{{ ticket.id }}</TableCell>
                                    
                                    <TableCell class="align-middle">
                                        <HoverCard v-if="ticket.reason.length >= 30">
                                            <HoverCardTrigger as-child>
                                                <div class="truncate cursor-pointer text-xs">{{ ticket.reason }}</div>
                                            </HoverCardTrigger>
                                            <HoverCardContent class="w-80">
                                                <p class="text-sm whitespace-normal break-words">{{ ticket.reason }}</p>
                                            </HoverCardContent>
                                        </HoverCard>
                                        <div v-else class="truncate text-xs">{{ ticket.reason }}</div>
                                    </TableCell>
                                    
                                    <TableCell class="align-middle text-xs">
                                        {{ ticket.user.name }}
                                    </TableCell>
                                    
                                    <TableCell class="align-middle text-xs">
                                        <HoverCard v-if="ticket.completion_notes && ticket.completion_notes.length >= 30">
                                            <HoverCardTrigger as-child>
                                                <div class="truncate cursor-pointer">{{ ticket.completion_notes }}</div>
                                            </HoverCardTrigger>
                                            <HoverCardContent class="w-80">
                                                <p class="text-sm whitespace-normal break-words">{{ ticket.completion_notes }}</p>
                                            </HoverCardContent>
                                        </HoverCard>
                                        <div v-else class="truncate">{{ ticket.completion_notes ?? '–' }}</div>
                                    </TableCell>
                                    
                                    <!-- Комментарий от пользователя -->
                                    <TableCell class="align-middle text-xs">
                                        <HoverCard v-if="ticket.comment && ticket.comment.length >= 30">
                                            <HoverCardTrigger as-child>
                                                <div class="truncate cursor-pointer">{{ ticket.comment }}</div>
                                            </HoverCardTrigger>
                                            <HoverCardContent class="w-80">
                                                <p class="text-sm whitespace-normal break-words">{{ ticket.comment }}</p>
                                            </HoverCardContent>
                                        </HoverCard>
                                        <div v-else-if="ticket.comment" class="truncate text-xs">{{ ticket.comment }}</div>
                                        <div v-else class="text-muted-foreground">–</div>
                                    </TableCell>
                                    
                                    <TableCell class="align-middle text-xs">{{ ticket.location }}</TableCell>
                                    
                                    <TableCell class="align-middle">
                                        <Badge :variant="ticket.status === 'new' ? 'outline' : 'secondary'" class="text-xs">
                                            {{ ticketStatusLabels[ticket.status] }}
                                        </Badge>
                                    </TableCell>
                                    
                                    <TableCell class="align-middle text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <Button
                                                @click="openTicketDialog(ticket)"
                                                variant="outline"
                                                size="icon"
                                                class="h-8 w-8"
                                                aria-label="Выполнено"
                                            >
                                                <Check class="w-3 h-3" />
                                            </Button>
                                            <Button
                                                @click="router.put(closedTicket(ticket.id).url, {}, {
                                                        preserveScroll: true,
                                                        preserveState: true
                                                    })"
                                                variant="outline"
                                                size="icon"
                                                class="h-8 w-8"
                                                aria-label="Отменить"
                                            >
                                                <X class="w-3 h-3" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </div>
                
                <!-- СОБЫТИЯ -->
                <div class="flex-1 flex flex-col min-h-0">
                    <div class="flex-shrink-0 py-4 pr-4">
                        <h4 class="font-medium">Запланированные события</h4>
                        <p class="mt-1 text-sm text-muted-foreground leading-relaxed">
                            События, созданные преподавателями.
                        </p>
                    </div>
                    
                    <div v-if="tasks.length === 0" class="flex-1 flex items-center justify-center text-muted-foreground">
                        Нет событий
                    </div>
                    
                    <div v-else class="flex-1 rounded-md border overflow-hidden flex flex-col min-h-0">
                        <Table class="w-full table-fixed border-collapse">
                            <TableHeader class="sticky top-0 bg-white z-20">
                                <TableRow class="border-b">
                                    <TableHead class="w-[50px]">ID</TableHead>
                                    <TableHead class="w-[150px]">Задача</TableHead>
                                    <TableHead class="w-[100px]">Имя</TableHead>
                                    <TableHead class="w-[100px]">Место</TableHead>
                                    <TableHead class="w-[120px]">Дата</TableHead>
                                    <TableHead class="w-[100px]">Заметки</TableHead>
                                    <TableHead class="w-[80px]">Статус</TableHead>
                                    <TableHead class="w-[100px] text-right">Действия</TableHead>
                                </TableRow>
                            </TableHeader>
                            
                            <TableBody>
                                <TableRow v-for="task in tasks" :key="task.id" :class="{'opacity-50 pointer-events-none': task.status !== 'new'}">
                                    <TableCell class="align-middle">{{ task.id }}</TableCell>
                                    
                                    <TableCell class="align-middle">
                                        <HoverCard v-if="task.description.length >= 20">
                                            <HoverCardTrigger as-child>
                                                <div class="truncate cursor-pointer text-xs">{{ task.description }}</div>
                                            </HoverCardTrigger>
                                            <HoverCardContent class="w-80">
                                                <p class="text-sm whitespace-normal break-words">{{ task.description }}</p>
                                            </HoverCardContent>
                                        </HoverCard>
                                        <div v-else class="truncate text-xs">{{ task.description }}</div>
                                    </TableCell>
                                    
                                    <TableCell class="align-middle text-xs">
                                        {{ task.teacher.name }}
                                    </TableCell>
                                    
                                    <TableCell class="align-middle text-xs">{{ task.location }}</TableCell>
                                    
                                    <TableCell class="align-middle text-xs">{{ task.scheduled_at_formatted }}</TableCell>
                                    
                                    <TableCell class="align-middle text-xs">
                                        <HoverCard v-if="task.completion_notes && task.completion_notes.length >= 30">
                                            <HoverCardTrigger as-child>
                                                <div class="truncate cursor-pointer">{{ task.completion_notes }}</div>
                                            </HoverCardTrigger>
                                            <HoverCardContent class="w-80">
                                                <p class="text-sm whitespace-normal break-words">{{ task.completion_notes }}</p>
                                            </HoverCardContent>
                                        </HoverCard>
                                        <div v-else class="truncate">{{ task.completion_notes ?? '–' }}</div>
                                    </TableCell>
                                    
                                    <TableCell class="align-middle">
                                        <Badge :variant="task.status === 'new' ? 'outline' : 'secondary'" class="text-xs">
                                            {{ taskStatusLabels[task.status] }}
                                        </Badge>
                                    </TableCell>
                                    
                                    <TableCell class="align-middle text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <Button
                                                @click="openTaskDialog(task)"
                                                variant="outline"
                                                size="icon"
                                                class="h-8 w-8"
                                                aria-label="Выполнено"
                                            >
                                                <Check class="w-3 h-3" />
                                            </Button>
                                            <Button
                                                @click="router.put(cancelledTask(task.id).url, {}, {
                                                        preserveScroll: true,
                                                        preserveState: true
                                                    })"
                                                variant="outline"
                                                size="icon"
                                                class="h-8 w-8"
                                                aria-label="Отменить"
                                            >
                                                <X class="w-3 h-3" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
    
    <!-- Dialog для завершения заявки -->
    <Dialog v-model:open="showTicketDialog">
        <DialogContent v-if="selectedTicket" class="sm:max-w-[525px]">
            <form @submit.prevent="updateTicketStatus">
                <DialogHeader>
                    <DialogTitle>Завершить заявку</DialogTitle>
                    <DialogDescription>Вы можете рассказать о том, какая работа была выполнена.</DialogDescription>
                </DialogHeader>
                
                <div class="my-8">
                    <label class="text-sm font-medium mb-2 block">
                        Заметки (не обязательно)
                    </label>
                    <Textarea
                        v-model="ticketForm.completion_notes"
                        placeholder="Опишите, что было сделано"
                        class="resize-none"
                    />
                </div>
                
                <DialogFooter>
                    <DialogClose as-child>
                        <Button variant="outline">Отмена</Button>
                    </DialogClose>
                    <Button type="submit" :disabled="ticketForm.processing">
                        Завершить
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
    
    <!-- Dialog для завершения события -->
    <Dialog v-model:open="showTaskDialog">
        <DialogContent v-if="selectedTask" class="sm:max-w-[525px]">
            <form @submit.prevent="updateTaskStatus">
                <DialogHeader>
                    <DialogTitle>Завершить событие</DialogTitle>
                    <DialogDescription>Вы можете рассказать о том, какая работа была выполнена.</DialogDescription>
                </DialogHeader>
                
                <div class="my-8">
                    <label class="text-sm font-medium mb-2 block">
                        Заметки (не обязательно)
                    </label>
                    <Textarea
                        v-model="taskForm.completion_notes"
                        placeholder="Опишите, что было сделано"
                        class="resize-none"
                    />
                </div>
                
                <DialogFooter>
                    <DialogClose as-child>
                        <Button variant="outline">Отмена</Button>
                    </DialogClose>
                    <Button type="submit" :disabled="taskForm.processing">
                        Завершить
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>

<style>
.two-tables-container {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 100px);
    overflow: hidden;
}
</style>