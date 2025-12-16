import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Index from "./pages/Index";
import Demande from "./pages/Demande";
import Suivi from "./pages/Suivi";
import Reclamation from "./pages/Reclamation";
import AdminLogin from "./pages/admin/Login";
import AdminDashboard from "./pages/admin/Dashboard";
import AdminDemandes from "./pages/admin/Demandes";
import AdminHistorique from "./pages/admin/Historique";
import AdminReclamations from "./pages/admin/Reclamations";
import NotFound from "./pages/NotFound";

const queryClient = new QueryClient();

const App = () => (
  <QueryClientProvider client={queryClient}>
    <TooltipProvider>
      <Toaster />
      <Sonner />
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Index />} />
          <Route path="/demande" element={<Demande />} />
          <Route path="/suivi" element={<Suivi />} />
          <Route path="/reclamation" element={<Reclamation />} />
          <Route path="/admin/login" element={<AdminLogin />} />
          <Route path="/admin" element={<AdminDashboard />} />
          <Route path="/admin/demandes" element={<AdminDemandes />} />
          <Route path="/admin/historique" element={<AdminHistorique />} />
          <Route path="/admin/reclamations" element={<AdminReclamations />} />
          <Route path="*" element={<NotFound />} />
        </Routes>
      </BrowserRouter>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;
