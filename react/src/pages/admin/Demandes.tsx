import { useState, useEffect } from "react";
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
  Filter,
  CheckCircle2,
  XCircle,
  Eye,
  Download,
  MoreHorizontal,
} from "lucide-react";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { apiEndpoints } from "@/lib/api";

const refusalReasons = [
  "Données incorrectes",
  "Document déjà délivré récemment",
  "Éléments manquants",
  "Erreur de filière",
];

export default function AdminDemandes() {
  const { isAuthenticated, isLoading } = useAdminAuth();
  const [requests, setRequests] = useState<DocumentRequest[]>([]);
  const [searchQuery, setSearchQuery] = useState("");
  const [typeFilter, setTypeFilter] = useState<string>("all");
  const [isLoadingData, setIsLoadingData] = useState(false);
  const [isInitialLoad, setIsInitialLoad] = useState(true);

  const [selectedRequest, setSelectedRequest] = useState<DocumentRequest | null>(null);
  const [showViewDialog, setShowViewDialog] = useState(false);
  const [showRefuseDialog, setShowRefuseDialog] = useState(false);
  const [refusalReason, setRefusalReason] = useState("");
  const [customReason, setCustomReason] = useState("");

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

  const filteredRequests = requests.filter((req) => {
    const matchesSearch =
      req.requestNumber.toLowerCase().includes(searchQuery.toLowerCase()) ||
      req.student?.nom.toLowerCase().includes(searchQuery.toLowerCase()) ||
      req.student?.apogee.includes(searchQuery);
    const matchesType = typeFilter === "all" || req.documentType === typeFilter;
    return matchesSearch && matchesType;
  });

  const handleValidate = async (request: DocumentRequest) => {
    try {
      const response = await apiEndpoints.validerDemande(request.id);
      
      if (response.data.success) {
        setRequests(
          requests.map((r) =>
            r.id === request.id ? { ...r, status: "validee" as RequestStatus, updatedAt: new Date() } : r
          )
        );
        toast.success(`Demande ${request.requestNumber} validée`);
      }
    } catch (error: any) {
      toast.error("Erreur lors de la validation");
    }
  };

  const handleRefuse = async () => {
    if (!selectedRequest) return;
    const reason = refusalReason === "other" ? customReason : refusalReason;
    if (!reason) {
      toast.error("Veuillez sélectionner ou saisir un motif de refus");
      return;
    }
    
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
          <div className="mb-8">
            <h1 className="text-3xl font-bold text-foreground">Gestion des demandes</h1>
            <p className="text-muted-foreground mt-1">
              Traitez et gérez les demandes des étudiants
            </p>
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
                  <TableHead>Date</TableHead>
                  <TableHead>Statut</TableHead>
                  <TableHead className="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {filteredRequests.map((request) => (
                  <TableRow key={request.id}>
                    <TableCell className="font-medium">{request.requestNumber}</TableCell>
                    <TableCell>
                      <div>
                        <p className="font-medium">
                          {request.student?.prenom} {request.student?.nom}
                        </p>
                        <p className="text-sm text-muted-foreground">
                          {request.student?.apogee}
                        </p>
                      </div>
                    </TableCell>
                    <TableCell>{documentTypeLabels[request.documentType]}</TableCell>
                    <TableCell>{request.createdAt.toLocaleDateString("fr-FR")}</TableCell>
                    <TableCell>
                      <StatusBadge status={request.status} />
                    </TableCell>
                    <TableCell className="text-right">
                      <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                          <Button variant="ghost" size="icon">
                            <MoreHorizontal className="h-4 w-4" />
                          </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                          <DropdownMenuItem
                            onClick={() => {
                              setSelectedRequest(request);
                              setShowViewDialog(true);
                            }}
                          >
                            <Eye className="mr-2 h-4 w-4" />
                            Voir détails
                          </DropdownMenuItem>
                          {(request.status === "en_attente" || request.status === "en_cours") && (
                            <>
                              <DropdownMenuSeparator />
                              <DropdownMenuItem
                                onClick={() => handleValidate(request)}
                                className="text-success"
                              >
                                <CheckCircle2 className="mr-2 h-4 w-4" />
                                Valider
                              </DropdownMenuItem>
                              <DropdownMenuItem
                                onClick={() => {
                                  setSelectedRequest(request);
                                  setShowRefuseDialog(true);
                                }}
                                className="text-destructive"
                              >
                                <XCircle className="mr-2 h-4 w-4" />
                                Refuser
                              </DropdownMenuItem>
                            </>
                          )}
                          {request.status === "validee" && (
                            <DropdownMenuItem>
                              <Download className="mr-2 h-4 w-4" />
                              Télécharger
                            </DropdownMenuItem>
                          )}
                        </DropdownMenuContent>
                      </DropdownMenu>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>

          {filteredRequests.length === 0 && (
            <div className="text-center py-12">
              <p className="text-muted-foreground">Aucune demande trouvée</p>
            </div>
          )}
        </div>
      </div>

      {/* View Dialog */}
      <Dialog open={showViewDialog} onOpenChange={setShowViewDialog}>
        <DialogContent className="max-w-lg">
          <DialogHeader>
            <DialogTitle>Détails de la demande</DialogTitle>
            <DialogDescription>{selectedRequest?.requestNumber}</DialogDescription>
          </DialogHeader>
          {selectedRequest && (
            <div className="space-y-4">
              <div className="grid grid-cols-2 gap-4 text-sm">
                <div>
                  <p className="text-muted-foreground">Étudiant</p>
                  <p className="font-medium">
                    {selectedRequest.student?.prenom} {selectedRequest.student?.nom}
                  </p>
                </div>
                <div>
                  <p className="text-muted-foreground">N° Apogée</p>
                  <p className="font-medium">{selectedRequest.student?.apogee}</p>
                </div>
                <div>
                  <p className="text-muted-foreground">CIN</p>
                  <p className="font-medium">{selectedRequest.student?.cin}</p>
                </div>
                <div>
                  <p className="text-muted-foreground">Email</p>
                  <p className="font-medium">{selectedRequest.student?.email}</p>
                </div>
                <div>
                  <p className="text-muted-foreground">Document</p>
                  <p className="font-medium">{documentTypeLabels[selectedRequest.documentType]}</p>
                </div>
                <div>
                  <p className="text-muted-foreground">Statut</p>
                  <StatusBadge status={selectedRequest.status} />
                </div>
              </div>
              {Object.keys(selectedRequest.details).length > 0 && (
                <div className="rounded-lg bg-muted/50 p-4">
                  <h4 className="font-semibold mb-2">Détails supplémentaires</h4>
                  <div className="grid gap-2 text-sm">
                    {Object.entries(selectedRequest.details).map(([key, value]) => (
                      <div key={key} className="flex justify-between">
                        <span className="text-muted-foreground capitalize">{key}:</span>
                        <span className="font-medium">{value}</span>
                      </div>
                    ))}
                  </div>
                </div>
              )}
            </div>
          )}
          <DialogFooter>
            <Button variant="outline" onClick={() => setShowViewDialog(false)}>
              Fermer
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Refuse Dialog */}
      <Dialog open={showRefuseDialog} onOpenChange={setShowRefuseDialog}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Refuser la demande</DialogTitle>
            <DialogDescription>
              Indiquez le motif du refus pour la demande {selectedRequest?.requestNumber}
            </DialogDescription>
          </DialogHeader>
          <div className="space-y-4">
            <div className="space-y-2">
              <Label>Motif du refus</Label>
              <Select value={refusalReason} onValueChange={setRefusalReason}>
                <SelectTrigger>
                  <SelectValue placeholder="Sélectionnez un motif" />
                </SelectTrigger>
                <SelectContent>
                  {refusalReasons.map((reason) => (
                    <SelectItem key={reason} value={reason}>
                      {reason}
                    </SelectItem>
                  ))}
                  <SelectItem value="other">Autre (préciser)</SelectItem>
                </SelectContent>
              </Select>
            </div>
            {refusalReason === "other" && (
              <div className="space-y-2">
                <Label>Motif personnalisé</Label>
                <Textarea
                  value={customReason}
                  onChange={(e) => setCustomReason(e.target.value)}
                  placeholder="Précisez le motif du refus..."
                  rows={3}
                />
              </div>
            )}
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setShowRefuseDialog(false)}>
              Annuler
            </Button>
            <Button variant="destructive" onClick={handleRefuse}>
              Confirmer le refus
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </Layout>
  );
}
