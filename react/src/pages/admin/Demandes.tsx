import { useState, useEffect } from "react";
import { useSearchParams } from "react-router-dom";
import { useAdminAuth } from "@/hooks/useAdminAuth";
import { Layout } from "@/components/layout/Layout";
import { DemandesSkeleton } from "@/components/shared/Skeleton";
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
  Tabs,
  TabsList,
  TabsTrigger
} from "@/components/ui/tabs";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { StatusBadge } from "@/components/shared/StatusBadge";
import { toast } from "sonner";
import {
  DocumentRequest,
  DocumentType,
  RequestStatus,
  documentTypeLabels,
} from "@/types";
import {
  Search,
  CheckCircle2,
  XCircle,
  Eye,
  Download,
  FileText,
  ArrowUpDown,
  ChevronUp,
  ChevronDown,
  Loader2,
} from "lucide-react";
import { apiEndpoints } from "@/lib/api";

const refusalReasons = [
  "Données incorrectes",
  "Document déjà délivré récemment",
  "Éléments manquants",
  "Erreur de filière",
];

export default function AdminDemandes() {
  const { isAuthenticated, isLoading } = useAdminAuth();
  const [searchParams] = useSearchParams();
  const initialSearch = searchParams.get("search") || "";
  
  const [requests, setRequests] = useState<DocumentRequest[]>([]);
  const [searchQuery, setSearchQuery] = useState(initialSearch);
  // If searching for a specific ID, show all types to ensure it's found
  const [typeFilter, setTypeFilter] = useState<string>(initialSearch ? "all" : "attestation_scolaire");
  const [sortConfig, setSortConfig] = useState<{ key: string; direction: 'asc' | 'desc' } | null>({ key: 'createdAt', direction: 'desc' });
  const [isLoadingData, setIsLoadingData] = useState(false);
  const [isInitialLoad, setIsInitialLoad] = useState(true);
  const [processingId, setProcessingId] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const [selectedRequest, setSelectedRequest] = useState<DocumentRequest | null>(null);
  const [showViewDialog, setShowViewDialog] = useState(false);
  const [showRefuseDialog, setShowRefuseDialog] = useState(false);
  const [showPreviewDialog, setShowPreviewDialog] = useState(false);
  const [pdfUrl, setPdfUrl] = useState<string | null>(null);
  const [isLoadingPdf, setIsLoadingPdf] = useState(false);
  const [refusalReason, setRefusalReason] = useState("");

  const [customReason, setCustomReason] = useState("");
  const [history, setHistory] = useState<any[]>([]);
  const [isLoadingHistory, setIsLoadingHistory] = useState(false);

  // Load demandes from API
  useEffect(() => {
    if (isAuthenticated) {
      loadDemandes();
    }
  }, [isAuthenticated, typeFilter, searchQuery]);

  const loadDemandes = async () => {
    if (!isInitialLoad) {
      setIsLoadingData(true);
    }
    try {
      const params: any = {};
      if (typeFilter !== "all") params.type_document = typeFilter;
      if (searchQuery) params.search = searchQuery;

      const response = await apiEndpoints.getDemandesAttente(params);

      if (response.data.success) {
        // Transform backend data to frontend format
        const transformedData = response.data.data.map((item: any) => ({
          id: item.id.toString(),
          requestNumber: item.num_demande,
          studentId: item.etudiant_id.toString(),
          student: item.etudiant ? {
            id: item.etudiant.id.toString(),
            email: item.etudiant.email,
            apogee: item.etudiant.apogee,
            cin: item.etudiant.cin,
            nom: item.etudiant.nom,
            prenom: item.etudiant.prenom,
            filiere: item.etudiant.filiere || "Non spécifié",
            niveau: item.etudiant.niveau || "Non spécifié",
          } : undefined,
          documentType: item.type_document as DocumentType,
          status: item.status as RequestStatus,
          createdAt: new Date(item.created_at),
          updatedAt: new Date(item.updated_at),
          details: getDocumentDetails(item),
          refusalReason: item.raison_refus,
        }));

        setRequests(transformedData);
      }
    } catch (error: any) {
      toast.error("Erreur lors du chargement des demandes");
      console.error("Load demandes error:", error);
    } finally {
      setIsLoadingData(false);
      setIsInitialLoad(false);
    }
  };

  const loadHistory = async (id: string) => {
    setIsLoadingHistory(true);
    try {
      const response = await apiEndpoints.getDemandeHistory(id);
      if (response.data.success) {
        setHistory(response.data.data);
      }
    } catch (error) {
      console.error("Failed to load history", error);
    } finally {
      setIsLoadingHistory(false);
    }
  };

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

  const handleSort = (key: string) => {
    let direction: 'asc' | 'desc' = 'asc';
    if (sortConfig && sortConfig.key === key && sortConfig.direction === 'asc') {
      direction = 'desc';
    }
    setSortConfig({ key, direction });
  };

  const filteredAndSortedRequests = (() => {
    let filtered = requests.filter((req) => {
      const student = req.student;
      const fullName = student ? `${student.prenom} ${student.nom}`.toLowerCase() : "";
      const reversedFullName = student ? `${student.nom} ${student.prenom}`.toLowerCase() : "";
      const query = searchQuery.toLowerCase();

      const matchesSearch =
        req.requestNumber.toLowerCase().includes(query) ||
        (student?.nom.toLowerCase().includes(query) ?? false) ||
        (student?.prenom.toLowerCase().includes(query) ?? false) ||
        fullName.includes(query) ||
        reversedFullName.includes(query) ||
        (student?.email.toLowerCase().includes(query) ?? false) ||
        (student?.apogee.includes(searchQuery) ?? false);

      const matchesType = typeFilter === "all" || req.documentType === typeFilter;
      return matchesSearch && matchesType;
    });

    if (sortConfig) {
      filtered.sort((a, b) => {
        let aValue: any = '';
        let bValue: any = '';

        if (sortConfig.key === 'requestNumber') {
          aValue = a.requestNumber;
          bValue = b.requestNumber;
        } else if (sortConfig.key === 'createdAt') {
          aValue = a.createdAt.getTime();
          bValue = b.createdAt.getTime();
        } else if (sortConfig.key === 'student') {
          aValue = `${a.student?.nom} ${a.student?.prenom}`;
          bValue = `${b.student?.nom} ${b.student?.prenom}`;
        }

        if (aValue < bValue) return sortConfig.direction === 'asc' ? -1 : 1;
        if (aValue > bValue) return sortConfig.direction === 'asc' ? 1 : -1;
        return 0;
      });
    }

    return filtered;
  })();

  const SortIndicator = ({ column }: { column: string }) => {
    if (!sortConfig || sortConfig.key !== column) return <ArrowUpDown className="ml-2 h-4 w-4 opacity-50" />;
    return sortConfig.direction === 'asc' ? <ChevronUp className="ml-2 h-4 w-4" /> : <ChevronDown className="ml-2 h-4 w-4" />;
  };

  const handlePreviewPDF = async (request: DocumentRequest) => {
    setSelectedRequest(request);
    setIsLoadingPdf(true);
    setShowPreviewDialog(true);

    try {
      const response = await apiEndpoints.previewPDF(request.id);

      if (response.data.success) {
        setPdfUrl(response.data.pdf_url);
      }
    } catch (error: any) {
      toast.error("Erreur lors de la prévisualisation du PDF");
      console.error("Preview PDF error:", error);
      setShowPreviewDialog(false);
    } finally {
      setIsLoadingPdf(false);
    }
  };

  const handleValidate = async (request: DocumentRequest) => {
    setProcessingId(request.id);
    try {
      const response = await apiEndpoints.validerDemande(request.id);

      if (response.data.success) {
        setRequests(
          requests.map((r) =>
            r.id === request.id ? { ...r, status: "validee" as RequestStatus, updatedAt: new Date() } : r
          )
        );
        toast.success(`Demande ${request.requestNumber} validée`);
        // Reload the list to remove validated request from pending list
        loadDemandes();
      }
    } catch (error: any) {
      toast.error("Erreur lors de la validation");
    } finally {
      setProcessingId(null);
    }
  };

  const handleValidateFromPreview = async () => {
    if (!selectedRequest) return;

    await handleValidate(selectedRequest);
    // handleValidate will handle error toast. If successful, it reloads demands.
    // We should close preview on success. Success is implied if we don't catch here,
    // but better to check if it's still in requests or status changed.
    setShowPreviewDialog(false);
    setSelectedRequest(null);
    setPdfUrl(null);
  };

  const handleRefuse = async () => {
    if (!selectedRequest) return;
    const reason = refusalReason === "other" ? customReason : refusalReason;
    if (!reason) {
      toast.error("Veuillez sélectionner ou saisir un motif de refus");
      return;
    }

    setIsSubmitting(true);
    setProcessingId(selectedRequest.id);
    try {
      const response = await apiEndpoints.refuserDemande(selectedRequest.id, { raison: reason });

      if (response.data.success) {
        setRequests(
          requests.map((r) =>
            r.id === selectedRequest.id
              ? { ...r, status: "rejetee" as RequestStatus, refusalReason: reason, updatedAt: new Date() }
              : r
          )
        );
        toast.success(`Demande ${selectedRequest.requestNumber} refusée`);
        setShowRefuseDialog(false);
        setSelectedRequest(null);
        setRefusalReason("");
        setCustomReason("");
      }
    } catch (error: any) {
      toast.error("Erreur lors du refus");
    } finally {
      setIsSubmitting(false);
      setProcessingId(null);
    }
  };

  if (isLoading || isInitialLoad) {
    return <DemandesSkeleton />;
  }

  if (!isAuthenticated) return null;

  return (
    <Layout showFooter={false}>
      <div className="min-h-[calc(100vh-4rem)] bg-muted/30 py-8">
        <div className="container">
          {/* Header */}
          <div className="mb-10">
            <h1 className="text-4xl font-extrabold text-foreground tracking-tight">Gestion des demandes</h1>
            <p className="text-lg text-muted-foreground mt-2">
              Traitez et gérez les demandes des étudiants avec efficacité
            </p>
          </div>

          <div className="rounded-xl border border-border bg-card p-6 mb-8 shadow-md">
            <div className="flex flex-col gap-8">
              <div className="flex flex-col lg:flex-row gap-4 items-center">
                <div className="flex-1 w-full relative">
                  <Search className="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-muted-foreground" />
                  <Input
                    placeholder="Rechercher par n° demande, nom, prénom, email ou Apogée..."
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    className="pl-12 h-14 text-base shadow-sm focus-visible:ring-primary"
                  />
                </div>
              </div>

              <div className="border-t pt-6">
                <Tabs value={typeFilter} onValueChange={setTypeFilter} className="w-full">
                  <TabsList className="bg-muted/50 p-1.5 h-14 flex flex-wrap lg:flex-nowrap gap-1">
                    {(Object.keys(documentTypeLabels) as DocumentType[]).map((type) => (
                      <TabsTrigger key={type} value={type} className="px-8 h-11 text-sm font-semibold whitespace-nowrap data-[state=active]:shadow-sm">
                        {documentTypeLabels[type]}
                      </TabsTrigger>
                    ))}
                  </TabsList>
                </Tabs>
              </div>
            </div>
          </div>

          {/* Table */}
          <div className="rounded-xl border border-border bg-card shadow-lg overflow-hidden transition-shadow duration-300 hover:shadow-xl">
            <Table>
              <TableHeader>
                <TableRow className="bg-muted/50 border-b-2">
                  <TableHead className="cursor-pointer hover:bg-muted py-5 text-sm font-bold uppercase tracking-wider" onClick={() => handleSort('requestNumber')}>
                    <div className="flex items-center">
                      N° Demande <SortIndicator column="requestNumber" />
                    </div>
                  </TableHead>
                  <TableHead className="cursor-pointer hover:bg-muted py-5 text-sm font-bold uppercase tracking-wider" onClick={() => handleSort('student')}>
                    <div className="flex items-center">
                      Étudiant <SortIndicator column="student" />
                    </div>
                  </TableHead>
                  <TableHead className="py-5 text-sm font-bold uppercase tracking-wider">Document</TableHead>
                  <TableHead className="cursor-pointer hover:bg-muted py-5 text-sm font-bold uppercase tracking-wider" onClick={() => handleSort('createdAt')}>
                    <div className="flex items-center">
                      Date <SortIndicator column="createdAt" />
                    </div>
                  </TableHead>
                  <TableHead className="py-5 text-sm font-bold uppercase tracking-wider">Statut</TableHead>
                  <TableHead className="text-right py-5 text-sm font-bold uppercase tracking-wider">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {isLoadingData ? (
                  <TableRow>
                    <TableCell colSpan={6} className="h-96 text-center">
                      <div className="flex flex-col items-center justify-center gap-4">
                        <div className="relative">
                          <Loader2 className="h-12 w-12 animate-spin text-primary" />
                          <div className="absolute inset-0 h-12 w-12 animate-pulse rounded-full bg-primary/10"></div>
                        </div>
                        <p className="text-xl font-semibold text-foreground">Chargement des demandes...</p>
                        <p className="text-muted-foreground">Veuillez patienter quelques instants</p>
                      </div>
                    </TableCell>
                  </TableRow>
                ) : filteredAndSortedRequests.length > 0 ? (
                  filteredAndSortedRequests.map((request) => (
                    <TableRow key={request.id} className="group transition-colors hover:bg-muted/30">
                      <TableCell className="font-bold py-5 text-base">{request.requestNumber}</TableCell>
                      <TableCell className="py-5">
                        <div className="flex flex-col gap-0.5">
                          <p className="font-bold text-base text-foreground group-hover:text-primary transition-colors">
                            {request.student?.prenom} {request.student?.nom}
                          </p>
                          <p className="text-sm font-medium text-muted-foreground">
                            {request.student?.apogee}
                          </p>
                        </div>
                      </TableCell>
                      <TableCell className="py-5 font-medium">{documentTypeLabels[request.documentType]}</TableCell>
                      <TableCell className="py-5 text-base font-medium">{request.createdAt.toLocaleDateString("fr-FR")}</TableCell>
                      <TableCell className="py-5">
                        <StatusBadge status={request.status} />
                      </TableCell>
                      <TableCell className="text-right py-5">
                        <div className="flex justify-end gap-3">
                          <Button
                            variant="outline"
                            size="icon"
                            className="h-10 w-10 shadow-sm hover:scale-105 transition-transform"
                            title="Voir détails"
                            onClick={() => {
                              setSelectedRequest(request);
                              loadHistory(request.id);
                              setShowViewDialog(true);
                            }}
                            disabled={processingId === request.id}
                          >
                            <Eye className="h-5 w-5" />
                          </Button>

                          {(request.status === "en_attente" || request.status === "en_cours") && (
                            <Button
                              variant="outline"
                              size="icon"
                              className="h-10 w-10 text-blue-600 border-blue-200 hover:bg-blue-50 shadow-sm hover:scale-105 transition-transform"
                              title="Prévisualiser PDF"
                              onClick={() => handlePreviewPDF(request)}
                              disabled={processingId === request.id}
                            >
                              <FileText className="h-5 w-5" />
                            </Button>
                          )}

                          {(request.status === "en_attente" || request.status === "en_cours") && (
                            <>
                              <Button
                                variant="outline"
                                size="icon"
                                className="h-10 w-10 text-success border-success/30 hover:bg-success/10 shadow-sm hover:scale-105 transition-transform"
                                title="Valider"
                                onClick={() => handleValidate(request)}
                                disabled={processingId === request.id}
                              >
                                {processingId === request.id ? (
                                  <Loader2 className="h-5 w-5 animate-spin" />
                                ) : (
                                  <CheckCircle2 className="h-5 w-5" />
                                )}
                              </Button>
                              <Button
                                variant="outline"
                                size="icon"
                                className="h-10 w-10 text-destructive border-destructive/30 hover:bg-destructive/10 shadow-sm hover:scale-105 transition-transform"
                                title="Refuser"
                                onClick={() => {
                                  setSelectedRequest(request);
                                  setShowRefuseDialog(true);
                                }}
                                disabled={processingId === request.id}
                              >
                                <XCircle className="h-5 w-5" />
                              </Button>
                            </>
                          )}

                          {request.status === "validee" && (
                            <Button
                              variant="outline"
                              size="icon"
                              className="h-10 w-10 text-primary border-primary/20 hover:bg-primary/5 shadow-sm hover:scale-105 transition-transform"
                              title="Télécharger"
                              disabled={processingId === request.id}
                            >
                              <Download className="h-5 w-5" />
                            </Button>
                          )}
                        </div>
                      </TableCell>
                    </TableRow>
                  ))
                ) : (
                  <TableRow>
                    <TableCell colSpan={6} className="h-96 text-center">
                      <div className="flex flex-col items-center justify-center gap-2">
                        <div className="rounded-full bg-muted p-6 mb-2">
                          <Search className="h-10 w-10 text-muted-foreground opacity-20" />
                        </div>
                        <p className="text-xl font-bold text-muted-foreground">Aucune demande trouvée</p>
                        <p className="text-muted-foreground max-w-xs mx-auto">Essayez d'ajuster vos filtres ou votre recherche pour trouver ce que vous cherchez.</p>
                      </div>
                    </TableCell>
                  </TableRow>
                )}
              </TableBody>
            </Table>
          </div>
        </div>
      </div>

      {/* View Dialog */}
      <Dialog open={showViewDialog} onOpenChange={setShowViewDialog}>
        <DialogContent className="max-w-2xl p-8">
          <DialogHeader className="mb-6">
            <DialogTitle className="text-2xl font-bold">Détails de la demande</DialogTitle>
            <DialogDescription className="text-lg font-medium text-primary">
              {selectedRequest?.requestNumber}
            </DialogDescription>
          </DialogHeader>
          {selectedRequest && (
            <div className="space-y-8">
              <div className="grid grid-cols-2 gap-8 text-base">
                <div className="space-y-1">
                  <p className="text-sm font-semibold uppercase tracking-wider text-muted-foreground">Étudiant</p>
                  <p className="font-bold text-lg">
                    {selectedRequest.student?.prenom} {selectedRequest.student?.nom}
                  </p>
                </div>
                <div className="space-y-1">
                  <p className="text-sm font-semibold uppercase tracking-wider text-muted-foreground">N° Apogée</p>
                  <p className="font-bold text-lg">{selectedRequest.student?.apogee}</p>
                </div>
                <div className="space-y-1">
                  <p className="text-sm font-semibold uppercase tracking-wider text-muted-foreground">CIN</p>
                  <p className="font-bold text-lg">{selectedRequest.student?.cin}</p>
                </div>
                <div className="space-y-1">
                  <p className="text-sm font-semibold uppercase tracking-wider text-muted-foreground">Email</p>
                  <p className="font-bold text-lg">{selectedRequest.student?.email}</p>
                </div>
                <div className="space-y-1">
                  <p className="text-sm font-semibold uppercase tracking-wider text-muted-foreground">Document</p>
                  <p className="font-bold text-lg">{documentTypeLabels[selectedRequest.documentType]}</p>
                </div>
                <div className="space-y-1">
                  <p className="text-sm font-semibold uppercase tracking-wider text-muted-foreground">Statut</p>
                  <div className="pt-1">
                    <StatusBadge status={selectedRequest.status} />
                  </div>
                </div>
              </div>

              {Object.keys(selectedRequest.details).length > 0 && (
                <div className="rounded-xl border border-border bg-muted/30 p-6 shadow-sm">
                  <h4 className="text-lg font-bold mb-4 flex items-center gap-2">
                    <FileText className="h-5 w-5 text-primary" />
                    Détails supplémentaires
                  </h4>
                  <div className="grid gap-4 text-base">
                    {Object.entries(selectedRequest.details).map(([key, value]) => (
                      <div key={key} className="flex justify-between border-b border-border/50 pb-2">
                        <span className="text-muted-foreground font-medium capitalize">{key}:</span>
                        <span className="font-bold">{value}</span>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* History Section */}
              <div className="rounded-xl border border-border bg-muted/30 p-6 shadow-sm">
                <h4 className="text-lg font-bold mb-4 flex items-center gap-2">
                  <ArrowUpDown className="h-5 w-5 text-primary" />
                  Historique des actions
                </h4>
                {isLoadingHistory ? (
                  <div className="flex items-center justify-center py-8">
                    <Loader2 className="h-8 w-8 animate-spin text-primary" />
                  </div>
                ) : history.length > 0 ? (
                  <div className="space-y-4">
                    {history.map((item: any, index: number) => (
                      <div key={index} className="flex gap-4 text-base border-l-4 border-primary/20 pl-4 py-1">
                        <div className="flex-1">
                          <p className="font-bold text-foreground">{item.action}</p>
                          {item.details && <p className="text-muted-foreground text-sm mt-1">{item.details}</p>}
                        </div>
                        <div className="text-xs font-bold text-muted-foreground bg-muted px-2 py-1 rounded h-fit self-start">
                          {new Date(item.created_at).toLocaleString('fr-FR')}
                        </div>
                      </div>
                    ))}
                  </div>
                ) : (
                  <div className="text-center py-4 bg-muted/50 rounded-lg">
                    <p className="text-muted-foreground font-medium">Aucun historique disponible</p>
                  </div>
                )}
              </div>
            </div>
          )}
          <DialogFooter className="mt-8">
            <Button variant="outline" size="lg" className="px-8 font-bold" onClick={() => setShowViewDialog(false)}>
              Fermer
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Refuse Dialog */}
      <Dialog open={showRefuseDialog} onOpenChange={setShowRefuseDialog}>
        <DialogContent className="max-w-xl p-8">
          <DialogHeader className="mb-6">
            <DialogTitle className="text-2xl font-bold text-destructive">Refuser la demande</DialogTitle>
            <DialogDescription className="text-lg">
              Veuillez indiquer le motif du refus pour la demande <span className="font-bold text-foreground">{selectedRequest?.requestNumber}</span>.
            </DialogDescription>
          </DialogHeader>
          <div className="space-y-6 py-4">
            <div className="space-y-3">
              <label className="text-base font-bold text-foreground">Motif du refus</label>
              <Select value={refusalReason} onValueChange={setRefusalReason}>
                <SelectTrigger className="h-12 text-base font-medium">
                  <SelectValue placeholder="Sélectionnez un motif" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="document_incomplet" className="py-3 text-base">Document incomplet</SelectItem>
                  <SelectItem value="informations_erronees" className="py-3 text-base">Informations erronées</SelectItem>
                  <SelectItem value="non_eligible" className="py-3 text-base">Étudiant non éligible</SelectItem>
                  <SelectItem value="frais_non_payes" className="py-3 text-base">Frais non payés</SelectItem>
                  <SelectItem value="other" className="py-3 text-base">Autre motif...</SelectItem>
                </SelectContent>
              </Select>
            </div>

            {refusalReason === "other" && (
              <div className="space-y-3 animate-in fade-in slide-in-from-top-2 duration-300">
                <label className="text-base font-bold text-foreground">Précisez le motif</label>
                <Textarea
                  placeholder="Saisissez le motif détaillé du refus..."
                  value={customReason}
                  onChange={(e) => setCustomReason(e.target.value)}
                  className="min-h-[120px] text-base font-medium p-4"
                />
              </div>
            )}
          </div>
          <DialogFooter className="mt-8 gap-3">
            <Button variant="outline" size="lg" className="px-8 font-bold" onClick={() => setShowRefuseDialog(false)} disabled={isSubmitting}>
              Annuler
            </Button>
            <Button variant="destructive" size="lg" className="px-8 font-bold shadow-lg shadow-destructive/20" onClick={handleRefuse} disabled={isSubmitting}>
              {isSubmitting ? (
                <>
                  <Loader2 className="mr-2 h-5 w-5 animate-spin" />
                  Traitement...
                </>
              ) : (
                "Confirmer le refus"
              )}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Preview PDF Dialog */}
      <Dialog open={showPreviewDialog} onOpenChange={(open) => {
        setShowPreviewDialog(open);
        if (!open) {
          setPdfUrl(null);
          setSelectedRequest(null);
        }
      }}>
        <DialogContent className="max-w-[95vw] w-full h-[95vh] flex flex-col p-8">
          <DialogHeader className="mb-6">
            <DialogTitle className="text-3xl font-extrabold text-foreground tracking-tight">
              Prévisualisation du PDF
            </DialogTitle>
            <DialogDescription className="text-xl font-medium text-primary">
              {selectedRequest?.requestNumber} — {selectedRequest && documentTypeLabels[selectedRequest.documentType]}
            </DialogDescription>
          </DialogHeader>
          <div className="flex-1 overflow-hidden rounded-lg border-2 border-border shadow-inner bg-muted/10">
            {isLoadingPdf ? (
              <div className="flex items-center justify-center h-full">
                <div className="text-center">
                  <div className="relative">
                    <Loader2 className="h-16 w-16 animate-spin text-primary mx-auto" />
                    <div className="absolute inset-0 h-16 w-16 animate-pulse rounded-full bg-primary/10 mx-auto"></div>
                  </div>
                  <p className="text-xl font-semibold text-foreground mt-6">Génération du PDF...</p>
                  <p className="text-muted-foreground mt-2">Veuillez patienter quelques instants</p>
                </div>
              </div>
            ) : pdfUrl ? (
              <iframe
                src={pdfUrl}
                className="w-full h-full rounded-lg"
                title="PDF Preview"
                style={{ border: 'none' }}
              />
            ) : (
              <div className="flex items-center justify-center h-full">
                <div className="text-center">
                  <div className="rounded-full bg-muted p-8 mb-4 inline-block">
                    <FileText className="h-12 w-12 text-muted-foreground opacity-20" />
                  </div>
                  <p className="text-xl font-bold text-muted-foreground">Impossible de charger le PDF</p>
                  <p className="text-muted-foreground mt-2">Veuillez réessayer ultérieurement</p>
                </div>
              </div>
            )}
          </div>
          <DialogFooter className="mt-6 gap-3">
            <Button
              variant="outline"
              size="lg"
              className="px-8 font-bold text-base"
              onClick={() => {
                if (pdfUrl) {
                  const link = document.createElement('a');
                  link.href = pdfUrl;
                  link.download = `${selectedRequest?.requestNumber}.pdf`;
                  link.click();
                }
              }}
              disabled={!pdfUrl || isLoadingPdf}
            >
              <Download className="mr-2 h-5 w-5" />
              Télécharger
            </Button>
            <Button
              variant="outline"
              size="lg"
              className="px-8 font-bold text-base"
              onClick={() => {
                setShowPreviewDialog(false);
                setPdfUrl(null);
                setSelectedRequest(null);
              }}
              disabled={processingId !== null}
            >
              Annuler
            </Button>
            <Button
              size="lg"
              onClick={handleValidateFromPreview}
              disabled={isLoadingPdf || processingId !== null}
              className="px-10 font-bold text-base bg-success hover:bg-success/90 shadow-lg shadow-success/20 min-w-[200px]"
            >
              {processingId === selectedRequest?.id ? (
                <>
                  <Loader2 className="mr-3 h-5 w-5 animate-spin" />
                  Traitement...
                </>
              ) : (
                <>
                  <CheckCircle2 className="mr-3 h-5 w-5" />
                  Valider et Envoyer
                </>
              )}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </Layout >
  );
}
