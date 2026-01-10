<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from '@/components/ui/table';
import {Check, X} from 'lucide-vue-next';
import {Head, Link, router} from '@inertiajs/vue3';
import {cancelledTask, closedTicket, completeTask, completeTicket, tasksCreate, ticketsCreate} from "@/routes";
import {Button} from "@/components/ui/button";
import {Badge} from "@/components/ui/badge";
import {HoverCard, HoverCardContent, HoverCardTrigger} from "@/components/ui/hover-card";

interface Ticket {
    id: number;
    title: string;
    reason: string;
    location: string;
    status: 'new' | 'completed' | 'closed';
    resolution_notes?: string;
    created_at: string;
}

interface Task {
    id: number;
    description: string;
    location: string;
    scheduled_at_formatted: string;
    status: 'scheduled' | 'completed' | 'cancelled';
    completion_notes?: string;
    assignee?: {
        id: number;
        name: string;
    };
}


const {tickets, tasks} = defineProps<{
    tickets: Ticket[];
    tasks: Task[];
}>();

const ticketStatusLabels: Record<string, string> = {
    'new': 'Новая',
    'completed': 'Выполнена',
    'closed': 'Закрыта'
};

const taskStatusLabels: Record<string, string> = {
    'scheduled': 'Запланировано',
    'completed': 'Завершена',
    'cancelled': 'Отменена'
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
                            Здесь отображаются заявки, оставленные пользователями.
                        </p>
                    </div>
                </div>
                
                <!-- Таблица -->
                <div class="rounded-md border">
                    <Table class="w-full table-fixed">
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[50px]">ID</TableHead>
                                <TableHead class="w-[300px]">Причина обращения</TableHead>
                                <TableHead class="w-[300px]">Примечания</TableHead>
                                <TableHead class="w-[200px]">Аудитория</TableHead>
                                <TableHead class="w-[100px]">Статус</TableHead>
                                <TableHead class="w-[120px] text-right">Действия</TableHead>
                            </TableRow>
                        </TableHeader>
                        
                        <TableBody>
                            <TableRow v-for="ticket in tickets" :key="ticket.id">
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
                                
                                
                                <!-- Примечания -->
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
                                
                                <TableCell class="align-middle">
                                    {{ ticket.location }}
                                </TableCell>
                                
                                <TableCell class="align-middle">
                                    <Badge
                                        :variant="ticket.status === 'new' ? 'outline' : 'secondary'"
                                    >
                                        {{ ticketStatusLabels[ticket.status] }}
                                    </Badge>
                                </TableCell>
                                
                                <TableCell class="align-middle text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Button
                                            @click="router.put(completeTicket(ticket.id).url)"
                                            variant="outline"
                                            size="icon"
                                            aria-label="Выполнено"
                                        >
                                            <Check class="w-4 h-4" />
                                        </Button>
                                        <Button
                                            @click="router.put(closedTicket(ticket.id).url)"
                                            variant="outline"
                                            size="icon"
                                            aria-label="Отменить"
                                        >
                                            <X class="w-4 h-4" />
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
            
            <!-- Если нет событий -->
            <div v-if="tasks.length === 0" class="flex items-center justify-center flex-1">
                <p>Список запланированных событий пуст.
                    <Link :href="tasksCreate().url" class="underline">Добавить</Link>
                </p>
            </div>
            
            <div v-else>
                <div class="flex items-center justify-between gap-8 py-4">
                    <div class="max-w-2xl">
                        <h4 class="font-medium">Запланированные события</h4>
                        
                        <p class="mt-1 text-sm text-muted-foreground leading-relaxed">
                            Здесь отображаются события, созданные преподавателями.
                        </p>
                    </div>
                </div>
                <!-- Таблица -->
                <div class="rounded-md border">
                    <Table class="w-full table-fixed">
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[50px]">ID</TableHead>
                                <TableHead class="w-[200px]">Задача</TableHead>
                                <TableHead>Место проведения</TableHead>
                                <TableHead>Дата и время</TableHead>
                                <TableHead class="w-[200px]">Примечания</TableHead>
                                <TableHead>Лаборант</TableHead>
                                <TableHead>Статус</TableHead>
                                <TableHead class="w-[120px] text-right">Действия</TableHead>
                            </TableRow>
                        </TableHeader>
                        
                        <TableBody>
                            <TableRow v-for="task in tasks" :key="task.id">
                                <TableCell class="align-middle">
                                    {{ task.id }}
                                </TableCell>
                                
                                <!-- Описание задачи -->
                                <TableCell class="align-middle">
                                    <HoverCard v-if="task.description.length >= 20">
                                        <HoverCardTrigger as-child>
                                            <div class="truncate cursor-pointer">
                                                {{ task.description }}
                                            </div>
                                        </HoverCardTrigger>
                                        
                                        <HoverCardContent class="w-96">
                                            <p class="text-sm whitespace-normal break-words">
                                                {{ task.description }}
                                            </p>
                                        </HoverCardContent>
                                    </HoverCard>
                                    
                                    <div v-else class="truncate">
                                        {{ task.description }}
                                    </div>
                                </TableCell>
                                
                                <!-- Место проведения -->
                                <TableCell class="align-middle">
                                    {{ task.location }}
                                </TableCell>
                                
                                <!-- Дата и время -->
                                <TableCell class="align-middle">
                                    {{ task.scheduled_at_formatted }}
                                </TableCell>
                                
                                <!-- Примечания -->
                                <TableCell class="align-middle">
                                    <HoverCard v-if="task.completion_notes && task.completion_notes.length >= 50">
                                        <HoverCardTrigger as-child>
                                            <div class="truncate cursor-pointer">
                                                {{ task.completion_notes }}
                                            </div>
                                        </HoverCardTrigger>
                                        
                                        <HoverCardContent class="w-96">
                                            <p class="text-sm whitespace-normal break-words">
                                                {{ task.completion_notes }}
                                            </p>
                                        </HoverCardContent>
                                    </HoverCard>
                                    
                                    <div v-else class="truncate">
                                        {{ task.completion_notes ?? '–' }}
                                    </div>
                                </TableCell>
                                
                                <!-- Лаборант -->
                                <TableCell class="align-middle">
                                    {{ task.assignee?.name ?? '—' }}
                                </TableCell>
                                
                                <TableCell class="align-middle">
                                    <Badge
                                        :variant="task.status === 'scheduled' ? 'outline' : 'secondary'"
                                    >
                                        {{ taskStatusLabels[task.status] }}
                                    </Badge>
                                </TableCell>
                                
                                <TableCell class="align-middle text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Button
                                            @click="router.put(completeTask(task.id).url)"
                                            variant="outline"
                                            size="icon"
                                            aria-label="Выполнено"
                                        >
                                            <Check class="w-4 h-4" />
                                        </Button>
                                        <Button
                                            @click="router.put(cancelledTask(task.id).url)"
                                            variant="outline"
                                            size="icon"
                                            aria-label="Отменить"
                                        >
                                            <X class="w-4 h-4" />
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
</template>