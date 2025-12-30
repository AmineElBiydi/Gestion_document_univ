import { useState, useEffect } from "react";
import { useAdminAuth } from "@/hooks/useAdminAuth";
import { Layout } from "@/components/layout/Layout";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import {
    PieChart,
    Pie,
    Cell,
    ResponsiveContainer,
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    Legend
} from "recharts";
import { apiEndpoints } from "@/lib/api";
import { useNavigate } from "react-router-dom";
import { ArrowRight, AlertTriangle, CheckCircle, Clock } from "lucide-react";

export default function ClaimsDashboard() {
    const { isAuthenticated } = useAdminAuth();
    const [stats, setStats] = useState<any>(null);
    const [isLoading, setIsLoading] = useState(true);
    const navigate = useNavigate();

    useEffect(() => {
        if (isAuthenticated) {
            loadStats();
        }
    }, [isAuthenticated]);

    const loadStats = async () => {
        setIsLoading(true);
        try {
            const response = await apiEndpoints.getReclamations({ status: 'all' });
            if (response.data.success) {
                const allClaims = response.data.data.data;

                // Calculate stats
                const total = allClaims.length;
                const pending = allClaims.filter((c: any) => c.status === 'non_traitee').length;
                const processing = allClaims.filter((c: any) => c.status === 'en_cours').length;
                const resolved = allClaims.filter((c: any) => c.status === 'traitee').length;

                // Type distribution
                const typeCount = allClaims.reduce((acc: any, curr: any) => {
                    acc[curr.type] = (acc[curr.type] || 0) + 1;
                    return acc;
                }, {});

                const typeData = Object.keys(typeCount).map(key => ({
                    name: key.replace('_', ' '),
                    value: typeCount[key]
                }));

                setStats({
                    total,
                    pending,
                    processing,
                    resolved,
                    typeData
                });
            }
        } catch (error) {
            console.error("Failed to load claims stats", error);
        } finally {
            setIsLoading(false);
        }
    };

    const COLORS = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042'];

    if (!isAuthenticated) return null;

    return (
        <Layout>
            <div className="min-h-screen bg-muted/30 py-8">
                <div className="container">
                    <div className="flex justify-between items-center mb-8">
                        <div>
                            <h1 className="text-3xl font-bold">Tableau de Bord des Réclamations</h1>
                            <p className="text-muted-foreground">Vue d'ensemble et analytiques</p>
                        </div>
                        <Button onClick={() => navigate('/admin/reclamations')}>
                            Gérer les réclamations <ArrowRight className="ml-2 h-4 w-4" />
                        </Button>
                    </div>

                    {isLoading ? (
                        <div>Chargement...</div>
                    ) : stats && (
                        <div className="space-y-6">
                            {/* Summary Cards */}
                            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <Card>
                                    <CardContent className="pt-6">
                                        <div className="text-2xl font-bold">{stats.total}</div>
                                        <p className="text-xs text-muted-foreground">Total Réclamations</p>
                                    </CardContent>
                                </Card>
                                <Card className="border-l-4 border-l-yellow-500">
                                    <CardContent className="pt-6 flex items-center justify-between">
                                        <div>
                                            <div className="text-2xl font-bold">{stats.pending}</div>
                                            <p className="text-xs text-muted-foreground">En attente</p>
                                        </div>
                                        <Clock className="h-8 w-8 text-yellow-500 opacity-20" />
                                    </CardContent>
                                </Card>
                                <Card className="border-l-4 border-l-blue-500">
                                    <CardContent className="pt-6 flex items-center justify-between">
                                        <div>
                                            <div className="text-2xl font-bold">{stats.processing}</div>
                                            <p className="text-xs text-muted-foreground">En cours</p>
                                        </div>
                                        <AlertTriangle className="h-8 w-8 text-blue-500 opacity-20" />
                                    </CardContent>
                                </Card>
                                <Card className="border-l-4 border-l-green-500">
                                    <CardContent className="pt-6 flex items-center justify-between">
                                        <div>
                                            <div className="text-2xl font-bold">{stats.resolved}</div>
                                            <p className="text-xs text-muted-foreground">Traitées</p>
                                        </div>
                                        <CheckCircle className="h-8 w-8 text-green-500 opacity-20" />
                                    </CardContent>
                                </Card>
                            </div>

                            {/* Charts */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <Card>
                                    <CardHeader>
                                        <CardTitle>Répartition par Type</CardTitle>
                                    </CardHeader>
                                    <CardContent className="h-[300px]">
                                        <ResponsiveContainer width="100%" height="100%">
                                            <PieChart>
                                                <Pie
                                                    data={stats.typeData}
                                                    cx="50%"
                                                    cy="50%"
                                                    outerRadius={80}
                                                    fill="#8884d8"
                                                    dataKey="value"
                                                    label
                                                >
                                                    {stats.typeData.map((entry: any, index: number) => (
                                                        <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                                                    ))}
                                                </Pie>
                                                <Tooltip />
                                                <Legend />
                                            </PieChart>
                                        </ResponsiveContainer>
                                    </CardContent>
                                </Card>

                                <Card>
                                    <CardHeader>
                                        <CardTitle>Statut des Réclamations</CardTitle>
                                    </CardHeader>
                                    <CardContent className="h-[300px]">
                                        <ResponsiveContainer width="100%" height="100%">
                                            <BarChart
                                                data={[
                                                    { name: 'En attente', value: stats.pending },
                                                    { name: 'En cours', value: stats.processing },
                                                    { name: 'Traitées', value: stats.resolved }
                                                ]}
                                            >
                                                <CartesianGrid strokeDasharray="3 3" />
                                                <XAxis dataKey="name" />
                                                <YAxis />
                                                <Tooltip />
                                                <Bar dataKey="value" fill="#8884d8">
                                                    {
                                                        [
                                                            { name: 'En attente', value: stats.pending },
                                                            { name: 'En cours', value: stats.processing },
                                                            { name: 'Traitées', value: stats.resolved }
                                                        ].map((entry, index) => (
                                                            <Cell key={`cell-${index}`} fill={index === 0 ? '#fbbf24' : index === 1 ? '#3b82f6' : '#22c55e'} />
                                                        ))
                                                    }
                                                </Bar>
                                            </BarChart>
                                        </ResponsiveContainer>
                                    </CardContent>
                                </Card>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </Layout>
    );
}
