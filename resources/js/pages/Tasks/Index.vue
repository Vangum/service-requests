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
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from '@/components/ui/table';
import {Pen, Trash} from 'lucide-vue-next';
import {Head, Link, router} from '@inertiajs/vue3';
import {tasksCreate, tasksEdit, ticketsDestroy} from "@/routes";
import {Button} from "@/components/ui/button";
import {Badge} from "@/components/ui/badge";
import {HoverCard, HoverCardContent, HoverCardTrigger} from "@/components/ui/hover-card";
import {FieldDescription, FieldLegend} from "@/components/ui/field";

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

const {tasks} = defineProps<{
    tasks: Task[];
}>();

const statusLabels: Record<string, string> = {
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
            <div v-if="tasks.length === 0" class="flex items-center justify-center flex-1">
                <p>Список запланированных событий пуст.
                    <Link :href="tasksCreate().url" class="underline">Добавить</Link>
                </p>
            </div>
            
            <div class="flex items-center justify-between gap-8 py-4">
                <div class="max-w-2xl">
                    <h4 class="font-medium">Запланированные события</h4>
                    
                    <p class="mt-1 text-sm text-muted-foreground leading-relaxed">
                        Здесь отображаются события, которые вы создали и назначили лаборантам.
                    </p>
                </div>
                
                <Button @click="router.get(tasksCreate().url)" class="shrink-0">Запланировать событие</Button>
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
                                <HoverCard v-if="task.description.length >= 50">
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
                                    :variant="task.status === 'scheduled' ? 'outline' : 'default'"
                                >
                                    {{ statusLabels[task.status] }}
                                </Badge>
                            </TableCell>
                            
                            <TableCell class="align-middle text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Button
                                        @click="router.get(tasksEdit(task.id).url)"
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
                                                <AlertDialogAction @click="router.delete(ticketsDestroy(task.id).url)">
                                                    Удалить
                                                </AlertDialogAction>
                                            </AlertDialogFooter>
                                        </AlertDialogContent>
                                    </AlertDialog>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>