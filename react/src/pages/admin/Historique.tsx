import { useState, useEffect } from "react";
import { useSearchParams } from "react-router-dom";
import { useAdminAuth } from "@/hooks/useAdminAuth";
import { Layout } from "@/components/layout/Layout";
import { HistoriqueSkeleton } from "@/components/shared/Skeleton";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Label } from "@/components/ui/label";
import { StatusBadge } from "@/components/shared/StatusBadge";
import { DocumentType, documentTypeLabels, RequestStatus } from "@/types";
import {
  Search,
  Download,
  FileSpreadsheet,
  FileText,
  Calendar,
  CheckCircle2,
  MessageSquare,
} from "lucide-react";
import { toast } from "sonner";
import { apiEndpoints } from "@/lib/api";

interface HistoryRecord {
  id: string;
  requestNumber: string;
  student?: {
    id: string;
    email: string;
    apogee: string;
    cin: string;
    nom: string;
    prenom: string;
    filiere: string;
    niveau: string;
  };
  documentType: DocumentType;
  status: RequestStatus;
  createdAt: Date;
  processedAt?: Date;
  processedBy?: string;
  refusalReason?: string;
  details: Record<string, string>;
  reclamation?: any;
}

export default function AdminHistorique() {
  const { isAuthenticated, isLoading } = useAdminAuth();
  const [searchParams] = useSearchParams();
  const initialSearch = searchParams.get("search") || "";

  const [history, setHistory] = useState<HistoryRecord[]>([]);
  const [isLoadingHistory, setIsLoadingHistory] = useState(false);
  const [isInitialLoad, setIsInitialLoad] = useState(true);
  const [searchQuery, setSearchQuery] = useState(initialSearch);
  const [statusFilter, setStatusFilter] = useState<string>("all");
  const [typeFilter, setTypeFilter] = useState<string>("all");

  const [selectedRecord, setSelectedRecord] = useState<HistoryRecord | null>(null);
  const [showReclamationDialog, setShowReclamationDialog] = useState(false);

  // Load history data from API
  useEffect(() => {
    if (isAuthenticated) {
      loadHistory();
    }
  }, [isAuthenticated]);

  const loadHistory = async () => {
    if (!isInitialLoad) {
      setIsLoadingHistory(true);
    }
    try {
      const response = await apiEndpoints.getHistorique({
        status: statusFilter !== "all" ? statusFilter : undefined,
        type_document: typeFilter !== "all" ? typeFilter : undefined,
        search: searchQuery || undefined,
      });

      if (response.data.success) {
        const demandes = response.data.data;
        console.log('Historique response data:', response.data);
        console.log('Demandes array:', demandes);

        // Transform API data to frontend format
        console.log('Starting transformation of', demandes.length, 'demands');
        const transformedHistory = demandes.map((demande: any) => {
          console.log('Transforming demande:', demande);
          return {
            id: demande.id.toString(),
            requestNumber: demande.num_demande || `DMD-${new Date(demande.created_at).getFullYear()}-${String(demande.id).padStart(4, '0')}`,
            student: demande.etudiant ? {
              id: demande.etudiant.id.toString(),
              email: demande.etudiant.email,
              apogee: demande.etudiant.apogee,
              cin: demande.etudiant.cin,
              nom: demande.etudiant.nom,
              prenom: demande.etudiant.prenom,
              filiere: demande.etudiant.filiere || "Non spécifié",
              niveau: demande.etudiant.niveau || "Non spécifié",
            } : undefined,
            documentType: demande.type_document as DocumentType,
            status: demande.status as RequestStatus,
            createdAt: new Date(demande.created_at),
            processedAt: new Date(demande.updated_at),
            processedBy: 'Admin', // TODO: Add admin tracking
            refusalReason: demande.raison_refus,
            details: getDocumentDetails(demande),
            reclamation: demande.reclamations && demande.reclamations.length > 0 ? demande.reclamations[0] : undefined,
          };
        });
        console.log('Transformed history:', transformedHistory);

        setHistory(transformedHistory);
      }
    } catch (error) {
      console.error("History loading error:", error);
      console.error("Error response:", error.response?.data);
      setHistory([]);
    } finally {
      setIsLoadingHistory(false);
      setIsInitialLoad(false);
    }
  };

  // Reload data when filters change
  useEffect(() => {
    if (isAuthenticated) {
      loadHistory();
    }
  }, [statusFilter, typeFilter, searchQuery]);

  const getDocumentDetails = (item: any): Record<string, string> => {
    const details: Record<string, string> = {};

    if (item.attestationScolaire) {
      Object.assign(details, item.attestationScolaire);
    }
    if (item.attestationReussite) {
      Object.assign(details, item.attestationReussite);
    }
    if (item.releveNotes) {
      Object.assign(details, item.releveNotes);
    }
    if (item.conventionStage) {
      Object.assign(details, item.conventionStage);
    }

    return details;
  };

  const downloadFile = (blob: Blob, filename: string) => {
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    link.remove();
    window.URL.revokeObjectURL(url);
  };

  const handleExport = async (format: "csv" | "pdf" | "excel") => {
    toast.info(`Export ${format.toUpperCase()} en cours...`);
    try {
      const params = {
        status: statusFilter !== "all" ? statusFilter : undefined,
        type_document: typeFilter !== "all" ? typeFilter : undefined,
        search: searchQuery || undefined,
      };

      let response;
      if (format === "csv") {
        response = await apiEndpoints.exportHistoriqueCSV(params);
      } else if (format === "excel") {
        response = await apiEndpoints.exportHistoriqueExcel(params);
      } else {
        response = await apiEndpoints.exportHistoriquePDF(params);
      }

      const extension = format === "excel" ? "xls" : format;
      const filename = `historique_${new Date().toISOString().slice(0, 10)}_${new Date().getTime()}.${extension}`;

      downloadFile(response.data, filename);
      toast.success(`Export ${format.toUpperCase()} réussi`);
    } catch (error) {
      console.error(`Export ${format} error:`, error);
      toast.error(`Erreur lors de l'export ${format.toUpperCase()}`);
    }
  };

  const handleReverse = async (id: string) => {
    try {
      const result = await apiEndpoints.reverserDemande(id);

      if (result.data.success) {
        toast.success("Demande inversée avec succès");
        // Reload the history to reflect the change
        loadHistory();
      }
    } catch (error: any) {
      toast.error(error.response?.data?.message || "Erreur lors de l'inversion");
    }
  };

  if (isLoading || isInitialLoad) {
    return <HistoriqueSkeleton />;
  }

  if (!isAuthenticated) return null;

  return (
    <Layout showFooter={false}>
      <div className="min-h-[calc(100vh-4rem)] bg-muted/30 py-8">
        <div className="container">
          {/* Header */}
          <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
            <div>
              <h1 className="text-3xl font-bold text-foreground">Historique</h1>
              <p className="text-muted-foreground mt-1">
                Consultez l'historique des demandes traitées
              </p>
            </div>
            <div className="flex gap-2">
              <Button variant="outline" onClick={() => handleExport("csv")}>
                <FileText className="mr-2 h-4 w-4" />
                CSV
              </Button>
              <Button variant="outline" onClick={() => handleExport("excel")}>
                <FileSpreadsheet className="mr-2 h-4 w-4" />
                Excel
              </Button>
              <Button variant="outline" onClick={() => handleExport("pdf")}>
                <Download className="mr-2 h-4 w-4" />
                PDF
              </Button>
            </div>
          </div>

          {/* Filters */}
          <div className="rounded-xl border border-border bg-card p-4 mb-6 shadow-sm">
            <div className="flex flex-col lg:flex-row gap-4">
              <div className="flex-1 relative">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                <Input
                  placeholder="Rechercher par n° demande, nom ou Apogée..."
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  className="pl-10"
                />
              </div>
              <div className="flex gap-4">
                <Select value={statusFilter} onValueChange={setStatusFilter}>
                  <SelectTrigger className="w-[160px]">
                    <SelectValue placeholder="Statut" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="all">Tous les statuts</SelectItem>
                    <SelectItem value="validee">Validée</SelectItem>
                    <SelectItem value="refusee">Refusée</SelectItem>
                  </SelectContent>
                </Select>
                <Select value={typeFilter} onValueChange={setTypeFilter}>
                  <SelectTrigger className="w-[200px]">
                    <SelectValue placeholder="Type de document" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="all">Tous les types</SelectItem>
                    {(Object.keys(documentTypeLabels) as DocumentType[]).map((type) => (
                      <SelectItem key={type} value={type}>
                        {documentTypeLabels[type]}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
            </div>
          </div>

          {/* Table */}
          <div className="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
            <Table>
              <TableHeader>
                <TableRow className="bg-muted/50">
                  <TableHead>N° Demande</TableHead>
                  <TableHead>Étudiant</TableHead>
                  <TableHead>Document</TableHead>
                  <TableHead>Créée le</TableHead>
                  <TableHead>Traitée le</TableHead>
                  <TableHead>Par</TableHead>
                  <TableHead>Statut</TableHead>
                  <TableHead>Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {isLoadingHistory ? (
                  <TableRow>
                    <TableCell colSpan={8} className="text-center py-8">
                      <div className="flex items-center justify-center">
                        <div className="animate-spin h-6 w-6 border-2 border-primary border-t-transparent rounded-full mr-2" />
                        Chargement...
                      </div>
                    </TableCell>
                  </TableRow>
                ) : history.length === 0 ? (
                  <TableRow>
                    <TableCell colSpan={8} className="text-center py-8">
                      <p className="text-muted-foreground">Aucun enregistrement trouvé</p>
                    </TableCell>
                  </TableRow>
                ) : (
                  history.map((record) => (
                    <TableRow key={record.id}>
                      <TableCell className="font-medium">{record.requestNumber}</TableCell>
                      <TableCell>
                        <div>
                          <p className="font-medium">
                            {record.student?.prenom} {record.student?.nom}
                          </p>
                          <p className="text-sm text-muted-foreground">
                            {record.student?.apogee}
                          </p>
                        </div>
                      </TableCell>
                      <TableCell>{documentTypeLabels[record.documentType]}</TableCell>
                      <TableCell>
                        <div className="flex items-center gap-1.5 text-sm">
                          <Calendar className="h-3.5 w-3.5 text-muted-foreground" />
                          {record.createdAt.toLocaleDateString("fr-FR")}
                        </div>
                      </TableCell>
                      <TableCell>
                        <div className="flex items-center gap-1.5 text-sm">
                          <Calendar className="h-3.5 w-3.5 text-muted-foreground" />
                          {record.processedAt?.toLocaleDateString("fr-FR") || "-"}
                        </div>
                      </TableCell>
                      <TableCell className="text-muted-foreground">{record.processedBy || "-"}</TableCell>
                      <TableCell>
                        <StatusBadge status={record.status} />
                      </TableCell>
                      <TableCell>
                        <div className="flex items-center gap-2">
                          {record.reclamation && (
                            <Button
                              variant="ghost"
                              size="sm"
                              onClick={() => {
                                setSelectedRecord(record);
                                setShowReclamationDialog(true);
                              }}
                              className="text-orange-500 hover:text-orange-600 hover:bg-orange-50"
                            >
                              <MessageSquare className="h-4 w-4 mr-1" />
                              Réclamation
                            </Button>
                          )}
                          {record.status === 'rejetee' && !record.reclamation && (
                            <Button
                              variant="outline"
                              size="sm"
                              onClick={() => handleReverse(record.id)}
                              className="text-green-600 hover:text-green-700 hover:bg-green-50"
                            >
                              <CheckCircle2 className="h-4 w-4 mr-1" />
                              Inverser
                            </Button>
                          )}
                        </div>
                      </TableCell>
                    </TableRow>
                  ))
                )}
              </TableBody>
            </Table>
          </div>

          {/* Pagination */}
          <div className="flex items-center justify-between mt-6">
            <p className="text-sm text-muted-foreground">
              Affichage de {history.length} résultat(s)
            </p>
            <div className="flex gap-2">
              <Button variant="outline" size="sm" disabled>
                Précédent
              </Button>
              <Button variant="outline" size="sm" disabled>
                Suivant
              </Button>
            </div>
          </div>
        </div>
      </div>


      <Dialog open={showReclamationDialog} onOpenChange={setShowReclamationDialog}>
        <DialogContent className="max-w-lg">
          <DialogHeader>
            <DialogTitle>Réclamation associée</DialogTitle>
            <DialogDescription>
              Demande: {selectedRecord?.requestNumber}
            </DialogDescription>
          </DialogHeader>
          {selectedRecord?.reclamation && (
            <div className="space-y-4">
              <div>
                <Label>Motif de la réclamation</Label>
                <div className="mt-1.5 p-3 rounded-lg bg-orange-50 border border-orange-100 text-sm">
                  <span className="font-semibold block mb-1 capitalize">
                    {selectedRecord.reclamation.type?.replace('_', ' ')}
                  </span>
                  {selectedRecord.reclamation.description}
                </div>
              </div>
              <div className="flex items-center gap-2 text-sm text-muted-foreground">
                <Calendar className="h-4 w-4" />
                Reçu le {new Date(selectedRecord.reclamation.created_at).toLocaleDateString("fr-FR")}
              </div>
            </div>
          )}
          <DialogFooter className="gap-2 sm:gap-0">
            <Button variant="outline" onClick={() => setShowReclamationDialog(false)}>
              Fermer
            </Button>
            {selectedRecord?.status === 'rejetee' && (
              <Button
                onClick={() => {
                  handleReverse(selectedRecord.id);
                  setShowReclamationDialog(false);
                }}
                className="bg-green-600 hover:bg-green-700 text-white"
              >
                <CheckCircle2 className="mr-2 h-4 w-4" />
                Accepter la réclamation & Inverser
              </Button>
            )}
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </Layout>
  );
}
