import { useState, useEffect } from "react";
import { Layout } from "@/components/layout/Layout";
import { StatsCard } from "@/components/shared/StatsCard";
import { DashboardSkeleton } from "@/components/shared/Skeleton";
import { useAdminAuth } from "@/hooks/useAdminAuth";
import {
  FileText,
  CheckCircle2,
  XCircle,
  Clock,
  AlertTriangle,
  TrendingUp,
  Users,
  Calendar,
  LogOut,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
  PieChart,
  Pie,
  Cell,
  LineChart,
  Line,
} from "recharts";
import { apiEndpoints } from "@/lib/api";

const statsData = [
  {
    title: "Total demandes",
    value: "1,247",
    description: "Ce mois",
    icon: FileText,
    trend: { value: 12, isPositive: true },
    variant: "primary" as const,
  },
  {
    title: "Demandes validées",
    value: "892",
    description: "71.5% du total",
    icon: CheckCircle2,
    trend: { value: 8, isPositive: true },
    variant: "success" as const,
  },
  {
    title: "En attente",
    value: "234",
    description: "À traiter",
    icon: Clock,
    trend: { value: 5, isPositive: false },
    variant: "warning" as const,
  },
  {
    title: "Réclamations",
    value: "23",
    description: "Non traitées",
    icon: AlertTriangle,
    trend: { value: 3, isPositive: false },
    variant: "destructive" as const,
  },
];

const barChartData = [
  { name: "Lun", demandes: 45 },
  { name: "Mar", demandes: 52 },
  { name: "Mer", demandes: 38 },
  { name: "Jeu", demandes: 65 },
  { name: "Ven", demandes: 48 },
  { name: "Sam", demandes: 12 },
  { name: "Dim", demandes: 5 },
];

const pieChartData = [
  { name: "Attestation scolarité", value: 45, color: "hsl(221, 83%, 30%)" },
  { name: "Relevé de notes", value: 30, color: "hsl(45, 93%, 47%)" },
  { name: "Attestation réussite", value: 15, color: "hsl(142, 76%, 36%)" },
  { name: "Convention stage", value: 10, color: "hsl(199, 89%, 48%)" },
];

const lineChartData = [
  { name: "Sem 1", traitees: 120, recues: 145 },
  { name: "Sem 2", traitees: 135, recues: 152 },
  { name: "Sem 3", traitees: 148, recues: 160 },
  { name: "Sem 4", traitees: 162, recues: 175 },
];

export default function AdminDashboard() {
  const { isAuthenticated, isLoading, logout } = useAdminAuth();
  const [statsData, setStatsData] = useState<any[]>([]);
  const [barChartData, setBarChartData] = useState<any[]>([]);
  const [pieChartData, setPieChartData] = useState<any[]>([]);
  const [reclStatusData, setReclStatusData] = useState<any[]>([]);
  const [reclTypeData, setReclTypeData] = useState<any[]>([]);
  const [lineChartData, setLineChartData] = useState<any[]>([]);
  const [isLoadingStats, setIsLoadingStats] = useState(false);

  // Load dashboard stats from API
  useEffect(() => {
    if (isAuthenticated) {
      loadDashboardStats();
    }
  }, [isAuthenticated]);

  const loadDashboardStats = async () => {
    setIsLoadingStats(true);
    try {
      const response = await apiEndpoints.getDashboard();
      
      if (response.data.success) {
        const stats = response.data.data;
        
        // Transform API data to frontend format
        const transformedStats = [
          {
            title: "Total demandes",
            value: stats.total_demandes.value.toString(),
            description: "Ce mois",
            icon: FileText,
            trend: { 
              value: Math.abs(stats.total_demandes.change), 
              isPositive: stats.total_demandes.trend === 'up' 
            },
            variant: "primary" as const,
          },
          {
            title: "Demandes validées",
            value: stats.validees.value.toString(),
            description: `${((stats.validees.value / stats.total_demandes.value) * 100).toFixed(1)}% du total`,
            icon: CheckCircle2,
            trend: { 
              value: Math.abs(stats.validees.change), 
              isPositive: stats.validees.trend === 'up' 
            },
            variant: "success" as const,
          },
          {
            title: "En attente",
            value: stats.en_attente.value.toString(),
            description: "À traiter",
            icon: Clock,
            trend: { 
              value: Math.abs(stats.en_attente.change), 
              isPositive: stats.en_attente.trend === 'down' 
            },
            variant: "warning" as const,
          },
          {
            title: "Réclamations",
            value: stats.reclamations.value.toString(),
            description: "Non traitées",
            icon: AlertTriangle,
            trend: { 
              value: Math.abs(stats.reclamations.change), 
              isPositive: stats.reclamations.trend === 'down' 
            },
            variant: "destructive" as const,
          },
        ];
        
        // Transform pie chart data from API
        const transformedPieData = [
          { name: "Attestation scolarité", value: stats.par_type.attestation_scolaire, color: "hsl(221, 83%, 30%)" },
          { name: "Relevé de notes", value: stats.par_type.releve_notes, color: "hsl(45, 93%, 47%)" },
          { name: "Attestation réussite", value: stats.par_type.attestation_reussite, color: "hsl(142, 76%, 36%)" },
          { name: "Convention stage", value: stats.par_type.convention_stage, color: "hsl(199, 89%, 48%)" },
        ];
        
        setStatsData(transformedStats);
        setPieChartData(transformedPieData);
        
        // Use real weekly data from API
        setBarChartData(stats.par_semaine);
        
        // Use real monthly performance data from API
        setLineChartData(stats.performance_mensuelle);

        // Transform reclamation stats
        if (stats.reclamations_par_status) {
          setReclStatusData([
            { name: "Non traitée", value: stats.reclamations_par_status.non_traitee, color: "hsl(var(--warning))" },
            { name: "En cours", value: stats.reclamations_par_status.en_cours, color: "hsl(var(--info))" },
            { name: "Traitée", value: stats.reclamations_par_status.traitee, color: "hsl(var(--success))" },
          ]);
        }

        if (stats.reclamations_par_type) {
          setReclTypeData([
            { name: "Retard", value: stats.reclamations_par_type.retard },
            { name: "Refus injustifié", value: stats.reclamations_par_type.refus_injustifie },
            { name: "Doc incorrect", value: stats.reclamations_par_type.document_incorrect },
            { name: "Problème Tech", value: stats.reclamations_par_type.probleme_technique },
          ]);
        }
      }
    } catch (error: any) {
      console.error("Dashboard stats error:", error);
      // Use mock data on error
      setStatsData([
        {
          title: "Total demandes",
          value: "0",
          description: "Ce mois",
          icon: FileText,
          trend: { value: 0, isPositive: true },
          variant: "primary" as const,
        },
        {
          title: "Demandes validées",
          value: "0",
          description: "0% du total",
          icon: CheckCircle2,
          trend: { value: 0, isPositive: true },
          variant: "success" as const,
        },
        {
          title: "En attente",
          value: "0",
          description: "À traiter",
          icon: Clock,
          trend: { value: 0, isPositive: false },
          variant: "warning" as const,
        },
        {
          title: "Réclamations",
          value: "0",
          description: "Non traitées",
          icon: AlertTriangle,
          trend: { value: 0, isPositive: false },
          variant: "destructive" as const,
        },
      ]);
      
      // Set empty chart data on error
      setPieChartData([
        { name: "Attestation scolarité", value: 0, color: "hsl(221, 83%, 30%)" },
        { name: "Relevé de notes", value: 0, color: "hsl(45, 93%, 47%)" },
        { name: "Attestation réussite", value: 0, color: "hsl(142, 76%, 36%)" },
        { name: "Convention stage", value: 0, color: "hsl(199, 89%, 48%)" },
      ]);
      
      setBarChartData([
        { name: "Lun", demandes: 0 },
        { name: "Mar", demandes: 0 },
        { name: "Mer", demandes: 0 },
        { name: "Jeu", demandes: 0 },
        { name: "Ven", demandes: 0 },
        { name: "Sam", demandes: 0 },
        { name: "Dim", demandes: 0 },
      ]);

      setLineChartData([
        { name: "Sem 1", traitees: 0, recues: 0 },
        { name: "Sem 2", traitees: 0, recues: 0 },
        { name: "Sem 3", traitees: 0, recues: 0 },
        { name: "Sem 4", traitees: 0, recues: 0 },
      ]);
    } finally {
      setIsLoadingStats(false);
    }
  };

  if (isLoading || isLoadingStats) {
    return <DashboardSkeleton />;
  }

  if (!isAuthenticated) return null;

  return (
    <Layout showFooter={false}>
      <div className="min-h-[calc(100vh-4rem)] bg-muted/30 py-8">
        <div className="container">
          {/* Header */}
          <div className="mb-8 flex items-center justify-between">
            <div>
              <h1 className="text-3xl font-bold text-foreground">Dashboard</h1>
              <p className="text-muted-foreground mt-1">
                Vue d'ensemble des demandes et statistiques
              </p>
            </div>
            <Button variant="outline" onClick={logout} className="gap-2">
              <LogOut className="h-4 w-4" />
              Déconnexion
            </Button>
          </div>

          {/* Stats Grid */}
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4 mb-8">
            {statsData.map((stat) => (
              <StatsCard key={stat.title} {...stat} />
            ))}
          </div>

          {/* Charts Grid */}
          <div className="grid gap-6 lg:grid-cols-2 mb-8">
            {/* Weekly Requests Chart */}
            <div className="rounded-xl border border-border bg-card p-6 shadow-sm">
              <div className="mb-6">
                <h3 className="font-semibold text-foreground">Demandes cette semaine</h3>
                <p className="text-sm text-muted-foreground">
                  Nombre de demandes par jour
                </p>
              </div>
              <ResponsiveContainer width="100%" height={250}>
                <BarChart data={barChartData}>
                  <CartesianGrid strokeDasharray="3 3" stroke="hsl(var(--border))" />
                  <XAxis dataKey="name" stroke="hsl(var(--muted-foreground))" fontSize={12} />
                  <YAxis stroke="hsl(var(--muted-foreground))" fontSize={12} />
                  <Tooltip
                    contentStyle={{
                      backgroundColor: "hsl(var(--card))",
                      border: "1px solid hsl(var(--border))",
                      borderRadius: "8px",
                    }}
                  />
                  <Bar dataKey="demandes" fill="hsl(var(--primary))" radius={[4, 4, 0, 0]} />
                </BarChart>
              </ResponsiveContainer>
            </div>

            {/* Document Types Pie Chart */}
            <div className="rounded-xl border border-border bg-card p-6 shadow-sm">
              <div className="mb-6">
                <h3 className="font-semibold text-foreground">Répartition par type</h3>
                <p className="text-sm text-muted-foreground">
                  Types de documents demandés
                </p>
              </div>
              <div className="flex items-center justify-center">
                <ResponsiveContainer width="100%" height={250}>
                  <PieChart>
                    <Pie
                      data={pieChartData}
                      cx="50%"
                      cy="50%"
                      innerRadius={60}
                      outerRadius={100}
                      paddingAngle={2}
                      dataKey="value"
                    >
                      {pieChartData.map((entry, index) => (
                        <Cell key={`cell-${index}`} fill={entry.color} />
                      ))}
                    </Pie>
                    <Tooltip
                      contentStyle={{
                        backgroundColor: "hsl(var(--card))",
                        border: "1px solid hsl(var(--border))",
                        borderRadius: "8px",
                      }}
                    />
                  </PieChart>
                </ResponsiveContainer>
              </div>
              <div className="grid grid-cols-2 gap-2 mt-4">
                {pieChartData.map((item) => (
                  <div key={item.name} className="flex items-center gap-2 text-sm">
                    <div
                      className="h-3 w-3 rounded-full"
                      style={{ backgroundColor: item.color }}
                    />
                    <span className="text-muted-foreground truncate">{item.name}</span>
                    <span className="font-medium ml-auto">{item.value}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Reclamations Analysis Grid */}
          <div className="grid gap-6 lg:grid-cols-2 mb-8">
            {/* Reclamation Status Pie Chart */}
            <div className="rounded-xl border border-border bg-card p-6 shadow-sm">
              <div className="mb-6">
                <h3 className="font-semibold text-foreground">État des réclamations</h3>
                <p className="text-sm text-muted-foreground">
                  Distribution par statut de traitement
                </p>
              </div>
              <div className="flex items-center justify-center">
                <ResponsiveContainer width="100%" height={250}>
                  <PieChart>
                    <Pie
                      data={reclStatusData}
                      cx="50%"
                      cy="50%"
                      innerRadius={60}
                      outerRadius={100}
                      paddingAngle={2}
                      dataKey="value"
                    >
                      {reclStatusData.map((entry, index) => (
                        <Cell key={`cell-${index}`} fill={entry.color} />
                      ))}
                    </Pie>
                    <Tooltip
                      contentStyle={{
                        backgroundColor: "hsl(var(--card))",
                        border: "1px solid hsl(var(--border))",
                        borderRadius: "8px",
                      }}
                    />
                  </PieChart>
                </ResponsiveContainer>
              </div>
              <div className="flex justify-center gap-4 mt-4">
                {reclStatusData.filter(i => i.value > 0).map((item) => (
                  <div key={item.name} className="flex items-center gap-2 text-sm">
                    <div
                      className="h-3 w-3 rounded-full"
                      style={{ backgroundColor: item.color }}
                    />
                    <span className="text-muted-foreground">{item.name}</span>
                    <span className="font-medium">({item.value})</span>
                  </div>
                ))}
              </div>
            </div>

            {/* Reclamation Type Bar Chart */}
            <div className="rounded-xl border border-border bg-card p-6 shadow-sm">
              <div className="mb-6">
                <h3 className="font-semibold text-foreground">Motifs de réclamation</h3>
                <p className="text-sm text-muted-foreground">
                  Classification par type de problème
                </p>
              </div>
              <ResponsiveContainer width="100%" height={250}>
                <BarChart data={reclTypeData} layout="vertical" margin={{ left: 40 }}>
                  <CartesianGrid strokeDasharray="3 3" stroke="hsl(var(--border))" horizontal={true} vertical={false} />
                  <XAxis type="number" stroke="hsl(var(--muted-foreground))" fontSize={12} allowDecimals={false} />
                  <YAxis dataKey="name" type="category" stroke="hsl(var(--muted-foreground))" fontSize={12} width={100} />
                  <Tooltip
                    contentStyle={{
                      backgroundColor: "hsl(var(--card))",
                      border: "1px solid hsl(var(--border))",
                      borderRadius: "8px",
                    }}
                  />
                  <Bar dataKey="value" fill="hsl(var(--destructive))" radius={[0, 4, 4, 0]} barSize={20} />
                </BarChart>
              </ResponsiveContainer>
            </div>
          </div>

          {/* Performance Chart */}
          <div className="rounded-xl border border-border bg-card p-6 shadow-sm">
            <div className="mb-6">
              <h3 className="font-semibold text-foreground">Performance mensuelle</h3>
              <p className="text-sm text-muted-foreground">
                Comparaison demandes reçues vs traitées
              </p>
            </div>
            <ResponsiveContainer width="100%" height={300}>
              <LineChart data={lineChartData}>
                <CartesianGrid strokeDasharray="3 3" stroke="hsl(var(--border))" />
                <XAxis dataKey="name" stroke="hsl(var(--muted-foreground))" fontSize={12} />
                <YAxis stroke="hsl(var(--muted-foreground))" fontSize={12} />
                <Tooltip
                  contentStyle={{
                    backgroundColor: "hsl(var(--card))",
                    border: "1px solid hsl(var(--border))",
                    borderRadius: "8px",
                  }}
                />
                <Line
                  type="monotone"
                  dataKey="recues"
                  stroke="hsl(var(--primary))"
                  strokeWidth={2}
                  dot={{ fill: "hsl(var(--primary))" }}
                  name="Reçues"
                />
                <Line
                  type="monotone"
                  dataKey="traitees"
                  stroke="hsl(var(--success))"
                  strokeWidth={2}
                  dot={{ fill: "hsl(var(--success))" }}
                  name="Traitées"
                />
              </LineChart>
            </ResponsiveContainer>
          </div>
        </div>
      </div>
    </Layout>
  );
}
