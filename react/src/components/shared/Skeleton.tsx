import { cn } from "@/lib/utils";
import { Layout } from "@/components/layout/Layout";

export function Skeleton({ className, ...props }: React.HTMLAttributes<HTMLDivElement>) {
  return (
    <div
      className={cn("animate-pulse rounded-md bg-muted", className)}
      {...props}
    />
  );
}

export function StatsCardSkeleton() {
  return (
    <div className="rounded-xl border border-border bg-card p-6 shadow-sm">
      <div className="flex items-center justify-between">
        <div className="space-y-2">
          <Skeleton className="h-4 w-24" />
          <Skeleton className="h-8 w-16" />
          <Skeleton className="h-3 w-32" />
        </div>
        <Skeleton className="h-12 w-12 rounded-xl" />
      </div>
      <div className="mt-4 flex items-center gap-2">
        <Skeleton className="h-4 w-4 rounded-full" />
        <Skeleton className="h-3 w-20" />
      </div>
    </div>
  );
}

export function TableSkeleton({ rows = 5 }: { rows?: number }) {
  return (
    <div className="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
      <div className="p-4 border-b border-border">
        <div className="flex gap-4">
          <Skeleton className="h-10 flex-1" />
          <Skeleton className="h-10 w-32" />
        </div>
      </div>
      <div className="overflow-x-auto">
        <table className="w-full">
          <thead className="bg-muted/50">
            <tr>
              {[1, 2, 3, 4, 5, 6, 7].map((i) => (
                <th key={i} className="px-4 py-3 text-left">
                  <Skeleton className="h-4 w-20" />
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {Array.from({ length: rows }).map((_, i) => (
              <tr key={i} className="border-b border-border">
                <td className="px-4 py-3">
                  <Skeleton className="h-4 w-24" />
                </td>
                <td className="px-4 py-3">
                  <div className="space-y-1">
                    <Skeleton className="h-4 w-32" />
                    <Skeleton className="h-3 w-20" />
                  </div>
                </td>
                <td className="px-4 py-3">
                  <Skeleton className="h-4 w-28" />
                </td>
                <td className="px-4 py-3">
                  <Skeleton className="h-4 w-24" />
                </td>
                <td className="px-4 py-3">
                  <Skeleton className="h-4 w-24" />
                </td>
                <td className="px-4 py-3">
                  <Skeleton className="h-4 w-16" />
                </td>
                <td className="px-4 py-3">
                  <Skeleton className="h-6 w-20 rounded-full" />
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

export function ReclamationCardSkeleton() {
  return (
    <div className="rounded-xl border border-border bg-card p-6 shadow-sm">
      <div className="flex gap-4">
        <Skeleton className="h-12 w-12 rounded-xl" />
        <div className="flex-1 space-y-3">
          <div className="flex items-center gap-3">
            <Skeleton className="h-5 w-32" />
            <Skeleton className="h-5 w-20 rounded-full" />
          </div>
          <div className="flex items-center gap-2 text-sm">
            <Skeleton className="h-4 w-4" />
            <Skeleton className="h-3 w-24" />
            <Skeleton className="h-3 w-16" />
          </div>
          <Skeleton className="h-4 w-full" />
          <Skeleton className="h-4 w-3/4" />
        </div>
        <div className="flex items-center gap-2">
          <Skeleton className="h-8 w-16" />
          <Skeleton className="h-8 w-16" />
        </div>
      </div>
    </div>
  );
}

export function ChartSkeleton({ height = 250 }: { height?: number }) {
  return (
    <div className="rounded-xl border border-border bg-card p-6 shadow-sm">
      <div className="mb-6 space-y-2">
        <Skeleton className="h-6 w-32" />
        <Skeleton className="h-4 w-48" />
      </div>
      <div style={{ height }} className="relative">
        <Skeleton className="h-full w-full" />
      </div>
    </div>
  );
}

export function DashboardSkeleton() {
  return (
    <Layout showFooter={false}>
      <div className="min-h-[calc(100vh-4rem)] bg-muted/30 py-8">
        <div className="container space-y-8">
          {/* Header */}
          <div className="space-y-2">
            <Skeleton className="h-9 w-48" />
            <Skeleton className="h-5 w-96" />
          </div>

          {/* Stats Grid */}
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <StatsCardSkeleton />
            <StatsCardSkeleton />
            <StatsCardSkeleton />
            <StatsCardSkeleton />
          </div>

          {/* Charts Grid */}
          <div className="grid gap-6 lg:grid-cols-2">
            <ChartSkeleton />
            <ChartSkeleton />
          </div>

          {/* Performance Chart */}
          <ChartSkeleton height={300} />
        </div>
      </div>
    </Layout>
  );
}

export function HistoriqueSkeleton() {
  return (
    <Layout showFooter={false}>
      <div className="min-h-[calc(100vh-4rem)] bg-muted/30 py-8">
        <div className="container space-y-8">
          {/* Header */}
          <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div className="space-y-2">
              <Skeleton className="h-9 w-32" />
              <Skeleton className="h-5 w-64" />
            </div>
            <div className="flex gap-2">
              <Skeleton className="h-10 w-16" />
              <Skeleton className="h-10 w-20" />
              <Skeleton className="h-10 w-16" />
            </div>
          </div>

          {/* Filters */}
          <div className="flex flex-col sm:flex-row gap-4">
            <div className="flex-1">
              <Skeleton className="h-10 w-full" />
            </div>
            <div className="flex gap-4">
              <Skeleton className="h-10 w-40" />
              <Skeleton className="h-10 w-48" />
            </div>
          </div>

          {/* Table */}
          <TableSkeleton rows={8} />
        </div>
      </div>
    </Layout>
  );
}

export function ReclamationsSkeleton() {
  return (
    <Layout showFooter={false}>
      <div className="min-h-[calc(100vh-4rem)] bg-muted/30 py-8">
        <div className="container space-y-8">
          {/* Header */}
          <div className="space-y-2">
            <Skeleton className="h-9 w-40" />
            <Skeleton className="h-5 w-72" />
          </div>

          {/* Stats */}
          <div className="grid gap-4 md:grid-cols-3">
            <div className="rounded-xl border border-border bg-card p-6 shadow-sm">
              <Skeleton className="h-8 w-8 mb-2" />
              <Skeleton className="h-8 w-16 mb-1" />
              <Skeleton className="h-4 w-32" />
            </div>
            <div className="rounded-xl border border-border bg-card p-6 shadow-sm">
              <Skeleton className="h-8 w-8 mb-2" />
              <Skeleton className="h-8 w-16 mb-1" />
              <Skeleton className="h-4 w-32" />
            </div>
            <div className="rounded-xl border border-border bg-card p-6 shadow-sm">
              <Skeleton className="h-8 w-8 mb-2" />
              <Skeleton className="h-8 w-16 mb-1" />
              <Skeleton className="h-4 w-32" />
            </div>
          </div>

          {/* Filters */}
          <div className="flex flex-col sm:flex-row gap-4">
            <div className="flex-1">
              <Skeleton className="h-10 w-full" />
            </div>
            <Skeleton className="h-10 w-48" />
          </div>

          {/* Reclamations List */}
          <div className="space-y-4">
            <ReclamationCardSkeleton />
            <ReclamationCardSkeleton />
            <ReclamationCardSkeleton />
            <ReclamationCardSkeleton />
          </div>
        </div>
      </div>
    </Layout>
  );
}

export function DemandesSkeleton() {
  return (
    <Layout showFooter={false}>
      <div className="min-h-[calc(100vh-4rem)] bg-muted/30 py-8">
        <div className="container space-y-8">
          {/* Header */}
          <div className="space-y-2">
            <Skeleton className="h-9 w-48" />
            <Skeleton className="h-5 w-80" />
          </div>

          {/* Filters */}
          <div className="rounded-xl border border-border bg-card p-4 shadow-sm">
            <div className="flex flex-col lg:flex-row gap-4">
              <div className="flex-1">
                <Skeleton className="h-10 w-full" />
              </div>
              <Skeleton className="h-10 w-48" />
            </div>
          </div>

          {/* Table */}
          <div className="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
            <div className="p-4">
              <div className="space-y-3">
                {/* Table Header */}
                <div className="grid grid-cols-6 gap-4">
                  <Skeleton className="h-4 w-20" />
                  <Skeleton className="h-4 w-32" />
                  <Skeleton className="h-4 w-24" />
                  <Skeleton className="h-4 w-16" />
                  <Skeleton className="h-4 w-16" />
                  <Skeleton className="h-4 w-20" />
                </div>
                
                {/* Table Rows */}
                {[...Array(5)].map((_, i) => (
                  <div key={i} className="grid grid-cols-6 gap-4 py-3 border-t border-border">
                    <Skeleton className="h-4 w-16" />
                    <Skeleton className="h-4 w-32" />
                    <Skeleton className="h-4 w-20" />
                    <Skeleton className="h-4 w-16" />
                    <Skeleton className="h-4 w-8" />
                    <Skeleton className="h-4 w-24" />
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Pagination */}
          <div className="flex items-center justify-between">
            <Skeleton className="h-4 w-32" />
            <div className="flex gap-2">
              <Skeleton className="h-8 w-20" />
              <Skeleton className="h-8 w-20" />
            </div>
          </div>
        </div>
      </div>
    </Layout>
  );
}
