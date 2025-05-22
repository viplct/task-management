import { mount } from '@vue/test-utils'
import TaskIndex from '@/views/tasks/TaskIndex.vue'
import { describe, it, expect, vi } from 'vitest'
import axios from 'axios'

// mock axios
vi.mock('axios', () => {
  const instance = {
    get: vi.fn(),
    post: vi.fn(),
    delete: vi.fn(),
    interceptors: { request: { use: vi.fn() }, response: { use: vi.fn() } }
  }

  return {
    default: {
      create: () => instance,
      ...instance
    }
  }
})

vi.mock('vue-router', async () => {
  const actual = await vi.importActual<typeof import('vue-router')>('vue-router')
  return {
    ...actual,
    useRoute: () => ({
      params: { projectId: '1' }
    })
  }
})

const mockedAxios = axios as unknown as {
  get: ReturnType<typeof vi.fn>
  post: ReturnType<typeof vi.fn>
  delete: ReturnType<typeof vi.fn>
}

describe('TaskIndex.vue', () => {
  it('loads and displays tasks', async () => {
    mockedAxios.get.mockResolvedValueOnce({
      data: {
        data: [
          {
            id: 1,
            title: 'Test Task',
            due_date: '2025-05-23',
            priority: 'medium',
            status: 'todo',
          },
        ],
      },
    })

    const wrapper = mount(TaskIndex, {
      global: {
        stubs: {
          DashboardLayout: {
            // ✅ This ensures your component content gets rendered
            template: '<div><slot /></div>'
          },
          TaskFormModal: true
        }
      }
    })

    // Wait for fetchTasks() to complete
    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()

    expect(wrapper.html()).toContain('Test Task')
  })

  it('shows empty message if no tasks', async () => {
    mockedAxios.get.mockResolvedValueOnce({ data: { data: [] } })

    const wrapper = mount(TaskIndex, {
      global: {
        stubs: {
          DashboardLayout: {
            template: '<div><slot /></div>' // render the default slot
          },
          TaskFormModal: true
        }
      }
    })

    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()
    expect(wrapper.text()).toContain('No tasks found.')
  })
})
